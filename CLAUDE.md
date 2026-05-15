# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

**csl-lslink** maps literary-source (`<ls>`) references in the CDSL dictionary XML files to their actual URLs. Each dictionary entry contains citations like `<ls>Rv. 1. 22. 16</ls>`; this repo resolves those citations to browsable links on the Cologne server.

The output is a per-dictionary SQLite database and a zip archive, committed here for distribution. Dictionaries currently covered: AP, GRA, MW, PW, PWG.

## Architecture

| File/Directory | Purpose |
|---|---|
| `lslinkscli.php` | Core PHP script: parses `<dict>.xml`, extracts `<ls>` references, resolves URLs, writes tab-delimited output |
| `make_sqlite.py` | Reads the tab-delimited output and creates `<dict>_lslinks.sqlite` |
| `redo_one_xampp.sh` | Orchestration script for one dictionary: runs PHP → Python → zip |
| `readme.txt` | Usage instructions and example log |
| `zip/` | Committed zip archives of the sqlite outputs (tracked by git) |

### Data flow

For each dictionary `<dict>`:
1. `lslinkscli.php <dict> <ap_dir> data/<dict>_lslinks.txt <dict>.xml` — parses XML, produces tab-delimited file with `ls_reference <TAB> url`
2. `make_sqlite.py` — loads the tab file into `sqlite/<dict>_lslinks.sqlite` (not tracked by git)
3. The sqlite is zipped into `zip/<dict>_lslinks.sqlite.zip` (tracked by git)

The `data/` and `sqlite/` directories are **not tracked** by git; only the `zip/` archives are committed.

## Common Commands

### Generate ls-links for one dictionary (XAMPP)
```bash
sh redo_one_xampp.sh <dict>
# e.g.: sh redo_one_xampp.sh mw
```

The script expects XAMPP conventions — it reads `<dict>.xml` from the local XAMPP webroot and writes output under the repo working directory.

## Dependencies

- **PHP** (CLI) — for parsing XML and resolving URLs
- **Python 3** — for `make_sqlite.py`
- **Dictionary XML files** — from the local XAMPP/Cologne installation at the path expected by `lslinkscli.php`
