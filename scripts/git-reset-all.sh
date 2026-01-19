#!/bin/bash
# Reset and clean all git repos in subfolders of given directory

DRYRUN=0
FOLDER=""

for arg in "$@"; do
    case $arg in
        --dryrun) DRYRUN=1 ;;
        *) FOLDER="$arg" ;;
    esac
done

[[ -z "$FOLDER" ]] && { echo "Usage: $0 [--dryrun] <folder>"; exit 1; }
[[ ! -d "$FOLDER" ]] && { echo "Error: '$FOLDER' is not a directory"; exit 1; }

# Process folder itself if it's a repo
if [[ -d "$FOLDER/.git" ]]; then
    dirs=("$FOLDER")
else
    dirs=()
fi

# Add subfolders that are repos (at any depth)
while IFS= read -r dir; do
    dirs+=("${dir%/.git}")
done < <(find "$FOLDER" -mindepth 2 -name .git -type d 2>/dev/null)

for dir in "${dirs[@]}"; do
    echo "=== $dir ==="
    if [[ $DRYRUN -eq 1 ]]; then
        reset_output=$(git -C "$dir" status --short 2>/dev/null)
        clean_output=$(git -C "$dir" clean -fdn 2>/dev/null)
        [[ -n "$reset_output" ]] && echo "Would reset:" && echo "$reset_output"
        [[ -n "$clean_output" ]] && echo "Would delete:" && echo "$clean_output"
    else
        git -C "$dir" reset --hard 2>/dev/null
        git -C "$dir" clean -fd 2>/dev/null
    fi
done
