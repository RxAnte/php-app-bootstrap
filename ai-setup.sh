#!/usr/bin/env bash

# Installs RxAnte AI project architecture guidance and points CLAUDE.md at it.
# Pulled with git over SSH rather than composer because this package is
# public and CI has no access to the private GitLab repo.
# Usage: ./ai-setup.sh [-f|--force]

set -euo pipefail;

cd "$(dirname "${BASH_SOURCE[0]}")";

REPO="git@gitlab.com:rxante/web-services/infrastructure/ai-project-architecture-guidance.git";
DEST="ai-project-architecture-guidance";
CACHE=".ai-guidance-last-checked";

force=false;

if [[ "${1:-}" == "-f" || "${1:-}" == "--force" ]]; then
    force=true;
fi

lastChecked=0;

if [[ -f "${CACHE}" ]]; then
    lastChecked=$(cat "${CACHE}");
fi

if [[ "${force}" == true || ! -d "${DEST}" || $(( $(date +%s) - lastChecked )) -ge 86400 ]]; then
    tmp=$(mktemp -d);
    trap 'rm -rf "${tmp}"' EXIT;

    git clone --depth 1 --quiet "${REPO}" "${tmp}/guidance";
    rm -rf "${tmp}/guidance/.git";

    rm -rf "${DEST}";
    mv "${tmp}/guidance" "${DEST}";

    date +%s > "${CACHE}";
fi

printf '@ai-project-architecture-guidance/CLAUDE.md' > CLAUDE.md;
