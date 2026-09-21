#!/usr/bin/env bash
x(){ echo "No script dir" >&2;return 1 2>/dev/null||exit 1;};if [ -n "${BASH_VERSION:-}" ];then s="${BASH_SOURCE[0]}";elif [ -n "${ZSH_VERSION:-}" ];then eval 's="${(%):-%x}"';else x;fi;[ -n "$s" ]||x;while [ -h "$s" ];do d="$(cd -P "$(dirname "$s")"&&pwd)"||x;s="$(readlink "$s")"||x;[[ $s != /* ]]&&s="$d/$s";done;__DIR__="$(cd -P "$(dirname "$s")"&&pwd)"||x;unset s d;unset -f x

# ========= Begin Configuration =========
PHP="$(command -v php)"
#PHP=/opt/homebrew/opt/php@8.5/bin/php

# Paths should be relative to THIS file:
INSTALL_PATH="../tests/"
CONFIG="../tests/phpunit.xml"
VENDOR="../vendor/"
# ========= End Configuration =========

# ========= Validation =========
[[ -z "$INSTALL_PATH" ]] && echo "❌️ \$INSTALL_PATH cannot be empty" && exit 3
INSTALL_PATH="$(cd "$__DIR__/$INSTALL_PATH" && pwd)"
CONFIG="$__DIR__/$CONFIG"
VENDOR="$(cd "$__DIR__/$VENDOR" && pwd)"
[[ -z "$VENDOR" ]] && echo "❌️ \$VENDOR cannot be empty" && exit 4
[[ ! -d  "$VENDOR" ]] && echo "❌️ \"$VENDOR\" does not exist; check the \$VENDOR variable in $0" && exit 5
[[ ! -f $VENDOR/bin/phpunit ]] && echo "❌️ missing dependencies; try \`composer install\`" && echo && exit 6
[[ -z "$PHP" ]] && echo "❌️ no PHP binary found; set \$PHP in $0" && exit 7
[[ ! -x "$PHP" ]] && echo "❌️ \"$PHP\" is not executable; check \$PHP in $0" && exit 8

# ========= Internal config =========
# shellcheck disable=SC2034
coverage_reports="$__DIR__/../reports/html"

export INSTALL_PATH

# ========= Force All PHP Errors To Be Displayed =========
# Many CLI builds (MAMP, some Homebrew formulae) ship display_errors=Off, so a
# fatal error produces a non-zero exit code and no output whatsoever -- the
# failure has to be hunted for instead of read.  A leading ':' makes PHP append
# this directory to its default scan path rather than replace it, so no php.ini
# is touched.  Exported so the PHPUnit subprocess inherits it.
php_ini_overrides="$(mktemp -d)"
trap 'rm -rf "$php_ini_overrides"' EXIT
cat > "$php_ini_overrides/zz-display-errors.ini" <<'PHP_INI'
display_errors = On
display_startup_errors = On
error_reporting = E_ALL
PHP_INI
export PHP_INI_SCAN_DIR="${PHP_INI_SCAN_DIR:-}:$php_ini_overrides"

# ========= Execute PHPUnit =========
#"$PHP" "$VENDOR/bin/phpunit" -c "$CONFIG" "$@"
#"$PHP" "$VENDOR/bin/phpunit" -c "$CONFIG" --testdox "$@"
export XDEBUG_MODE="${XDEBUG_MODE:-},coverage"
"$PHP" "$VENDOR/bin/phpunit" -c "$CONFIG" --coverage-html="$coverage_reports" "$@"
# Capture PHPUnit's status before the echo, or the echo becomes the script's
# exit code and a failing suite reports success.
phpunit_status=$?
echo "$coverage_reports/index.html"
exit $phpunit_status
