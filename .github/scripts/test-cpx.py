"""Exercise the committed package through CPX, without Packagist branch refs."""

import hashlib
import json
import os
from pathlib import Path
import subprocess
import tempfile
import zipfile


ROOT = Path(__file__).resolve().parents[2]


def run(command, *, cwd=ROOT, env=None, expected=0):
    result = subprocess.run(command, cwd=cwd, env=env, capture_output=True, text=True)
    if result.returncode != expected:
        raise RuntimeError(
            f"{command} exited {result.returncode}, expected {expected}\n"
            f"{result.stdout}\n{result.stderr}"
        )
    return result.stdout


def check(condition, message):
    if not condition:
        raise RuntimeError(message)


# A fixed Composer version avoids CPX's rejection of slash-containing branches.
# The archive still comes from the exact checkout (including PR merge commits).
revision = run(["git", "rev-parse", "HEAD"]).strip()
with tempfile.TemporaryDirectory(prefix="publicip-cpx-") as directory:
    work = Path(directory)
    archive = work / "publicip.zip"
    run(["git", "archive", "--format=zip", f"--output={archive}", "HEAD"])
    with zipfile.ZipFile(archive) as package_zip:
        manifest = json.loads(package_zip.read("composer.json"))
        check(not any(name.startswith("vendor/") for name in package_zip.namelist()),
              "Package archive must not contain checkout dependencies")

    # Keep the CPX tool install separate from the empty consumer and its cache.
    env = {"PATH": os.environ["PATH"], "HOME": str(work / "home"),
           "TMPDIR": str(work), "COMPOSER_HOME": str(work / "tool"),
           "COMPOSER_CACHE_DIR": str(work / "composer-cache"),
           "COMPOSER_NO_INTERACTION": "1", "XDG_CONFIG_HOME": str(work / "config"),
           "CPX_HOME": str(work / "cpx")}
    Path(env["HOME"]).mkdir()
    run(["composer", "global", "require", "cpx/cpx", "--no-interaction",
         "--prefer-dist", "--no-progress"], env=env)
    cpx = str(work / "tool/vendor/bin/cpx")
    manifest.update(version="dev-cpx-pr", dist={
        "type": "zip", "url": archive.as_uri(), "reference": revision,
        "shasum": hashlib.sha1(archive.read_bytes()).hexdigest(),
    })
    consumer_home = work / "consumer-composer"
    consumer_home.mkdir()
    (consumer_home / "config.json").write_text(json.dumps({
        "repositories": [{"type": "package", "package": manifest}]
    }))
    env["COMPOSER_HOME"] = str(consumer_home)
    consumer = work / "consumer"
    consumer.mkdir()
    command = [cpx, "knotsphp/publicip:dev-cpx-pr", "--help"]
    output = run(command, cwd=consumer, env=env)
    check("Usage: publicip [options]" in output and "--v4" in output,
          f"CPX did not execute publicip: {output}")
    print(output.strip())

    locks = list((work / "cpx").rglob("composer.lock"))
    check(len(locks) == 1, f"Expected one isolated CPX install, found {locks}")
    packages = json.loads(locks[0].read_text())["packages"]
    check(len(packages) == 1 and packages[0]["name"] == "knotsphp/publicip",
          "Unexpected dependencies (require-dev must not be installed)")
    check(packages[0]["dist"] == manifest["dist"], "Installed archive/revision mismatch")
    installed = locks[0].parent / "vendor/knotsphp/publicip"
    check(not installed.is_symlink(), "CPX must install the archive, not a path symlink")
    binary = installed / "bin/publicip"
    original = binary.read_bytes()
    check(original == subprocess.check_output(["git", "show", "HEAD:bin/publicip"], cwd=ROOT),
          "Installed executable differs from the committed executable")
    check(not (installed / "vendor").exists(), "Checkout vendor directory leaked into package")

    # A successful runner exit must not conceal a failing child executable.
    try:
        binary.write_text("#!/usr/bin/env php\n<?php fwrite(STDERR, 'cpx-child-failure'); exit(23);\n")
        result = subprocess.run(command, cwd=consumer, env=env, capture_output=True, text=True)
        check(result.returncode == 23 and "cpx-child-failure" in result.stderr,
              f"CPX masked the child failure: {result}")
    finally:
        binary.write_bytes(original)
    print(f"CPX installed and executed {revision}; child failure exit 23 preserved.")
