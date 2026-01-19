#!/bin/bash
# Remove duplicate words from CSV files (keeps first occurrence)

for file in "$(dirname "$0")"/words.*.csv; do
    if [[ -f "$file" ]]; then
        echo "Processing: $file"
        # Keep header + unique lines based on first column (word)
        awk -F',' 'NR==1 || !seen[$1]++' "$file" > "$file.tmp" && mv "$file.tmp" "$file"
        echo "  Done: $(wc -l < "$file") lines"
    fi
done
