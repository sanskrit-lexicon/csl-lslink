# csl-lslink

_Created: 21-06-2026 · Last updated: 11-07-2026_

**Linking-tool** repository in the [Cologne Digital Sanskrit Lexicon](https://github.com/sanskrit-lexicon) (CDSL) project. It resolves the literary-source citations (`<ls>` references) found inside CDSL dictionary entries into concrete link-target URLs, and packages the resulting mappings as compressed SQLite databases keyed by dictionary code.

This is the data-generation half of the Dictionary-to-Book (`<ls>` citation link-target) workflow: where [`/cologne-link-target`](https://github.com/gasyoun/claude-config/blob/main/commands/cologne-link-target.md) inventories and maps `<ls>` abbreviations to scanned-edition pages, this repo runs the mapping over every entry and emits the per-dictionary `ls → href` lookup tables the web display consumes.

## What it produces

For each supported dictionary, a two-column tab-delimited table (`ls` reference → target URL) is built and stored as a zipped SQLite database under [`zip/`](https://github.com/sanskrit-lexicon/csl-lslink/tree/main/zip). Currently tracked (7 dictionaries):

| Dictionary code | Artifact |
|---|---|
| ap | [`zip/ap_lslinks.sqlite.zip`](https://github.com/sanskrit-lexicon/csl-lslink/blob/main/zip/ap_lslinks.sqlite.zip) |
| ap90 | [`zip/ap90_lslinks.sqlite.zip`](https://github.com/sanskrit-lexicon/csl-lslink/blob/main/zip/ap90_lslinks.sqlite.zip) |
| gra | [`zip/gra_lslinks.sqlite.zip`](https://github.com/sanskrit-lexicon/csl-lslink/blob/main/zip/gra_lslinks.sqlite.zip) |
| mw | [`zip/mw_lslinks.sqlite.zip`](https://github.com/sanskrit-lexicon/csl-lslink/blob/main/zip/mw_lslinks.sqlite.zip) |
| pw | [`zip/pw_lslinks.sqlite.zip`](https://github.com/sanskrit-lexicon/csl-lslink/blob/main/zip/pw_lslinks.sqlite.zip) |
| pwg | [`zip/pwg_lslinks.sqlite.zip`](https://github.com/sanskrit-lexicon/csl-lslink/blob/main/zip/pwg_lslinks.sqlite.zip) |
| sch | [`zip/sch_lslinks.sqlite.zip`](https://github.com/sanskrit-lexicon/csl-lslink/blob/main/zip/sch_lslinks.sqlite.zip) |

The intermediate `data/` (tab-delimited text) and unzipped `sqlite/` directories are **not** tracked by git (see [`.gitignore`](https://github.com/sanskrit-lexicon/csl-lslink/blob/main/.gitignore)); only the zipped SQLite artifacts are committed. See [`readme.txt`](https://github.com/sanskrit-lexicon/csl-lslink/blob/main/readme.txt) for a worked example log.

## Pipeline

The end-to-end regeneration for one dictionary is driven by [`redo_one_xampp.sh`](https://github.com/sanskrit-lexicon/csl-lslink/blob/main/redo_one_xampp.sh), which takes a single lower-case dictionary code:

```
sh redo_one_xampp.sh ap
```

Its steps:

1. [`lslinkscli.php`](https://github.com/sanskrit-lexicon/csl-lslink/blob/main/lslinkscli.php) reads the dictionary's `pywork/<dict>.xml` from a local CDSL installation, runs each record through the `BasicAdjust` class from `basicadjust.php`, and writes `data/<dict>_lslinks.txt` (two tab-delimited fields: the `ls` match string and its href).
2. [`make_sqlite.py`](https://github.com/sanskrit-lexicon/csl-lslink/blob/main/make_sqlite.py) loads that text file into a SQLite database (`sqlite/<dict>_lslinks.sqlite`) with a `key`/`data` table, indexed on `key`, inserted in batches (default 10000 rows).
3. The `.sqlite` is zipped into [`zip/`](https://github.com/sanskrit-lexicon/csl-lslink/tree/main/zip) — the tracked deliverable.

## Requirements

This tool is **not** self-contained; it runs against a local CDSL installation:

- **PHP** (the log example uses XAMPP's `/c/xampp/php/php.exe`) to run `lslinkscli.php`.
- **`basicadjust.php`** from [`csl-websanlexicon`](https://github.com/sanskrit-lexicon/csl-websanlexicon) — the source of `<ls>` reference resolution. Pull that repo first for the latest `basicadjust.php`; the linking only works for dictionaries whose `ls` references are known to `basicadjust` (confirmed for pw, pwg, mw, gra, sch, and the Apte pair).
- **Python 3** with the standard library (`sqlite3`, `codecs`) for `make_sqlite.py`.
- The target dictionary's local display generated from `csl-pywork/v02`, plus `zip`/`wc`/`du` shell utilities.

## Repository files

| File | Purpose |
|---|---|
| [`lslinkscli.php`](https://github.com/sanskrit-lexicon/csl-lslink/blob/main/lslinkscli.php) | Extracts `ls → href` pairs from dictionary XML via `BasicAdjust` |
| [`make_sqlite.py`](https://github.com/sanskrit-lexicon/csl-lslink/blob/main/make_sqlite.py) | Loads the tab-delimited pairs into an indexed SQLite database |
| [`redo_one_xampp.sh`](https://github.com/sanskrit-lexicon/csl-lslink/blob/main/redo_one_xampp.sh) | Orchestrates the full pipeline for one dictionary code |
| [`readme.txt`](https://github.com/sanskrit-lexicon/csl-lslink/blob/main/readme.txt) | Original notes + worked example log |
| [`zip/`](https://github.com/sanskrit-lexicon/csl-lslink/tree/main/zip) | Tracked zipped SQLite lslink databases |
| [`CLAUDE.md`](https://github.com/sanskrit-lexicon/csl-lslink/blob/main/CLAUDE.md) | Repo-specific guidance for Claude Code sessions |

## GitHub issue conventions

Follows the [Cologne tooling-repo taxonomy](https://github.com/sanskrit-lexicon/csl-observatory/blob/main/runbook/cologne-tooling-runbook.md): exactly one type label, one severity level, and one milestone per issue, scoped to the `linking-tool` category — see [`CLAUDE.md`](https://github.com/sanskrit-lexicon/csl-lslink/blob/main/CLAUDE.md) for the full label set. Tool work across the org is tracked in the [Tooling Roadmap](https://github.com/orgs/sanskrit-lexicon/projects/9) project.

Open issues are visible at [github.com/sanskrit-lexicon/csl-lslink/issues](https://github.com/sanskrit-lexicon/csl-lslink/issues) (the authoritative live count — no stale snapshot is kept here).

## License

This repository contains both source code and dictionary/data files, which are
licensed separately:

- **Source code** (e.g. `*.py`, `*.php`, `*.js`, `*.sh`) is licensed under the
  **GNU General Public License v3.0** — see
  [`licenses/GPL-3.0.txt`](https://github.com/sanskrit-lexicon/csl-lslink/blob/main/licenses/GPL-3.0.txt).
- **Dictionary and data files** are licensed under **Creative Commons
  Attribution-ShareAlike 4.0 International (CC-BY-SA-4.0)** — see
  [`LICENSE`](https://github.com/sanskrit-lexicon/csl-lslink/blob/main/LICENSE).

_Dr. Mārcis Gasūns_
