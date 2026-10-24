$roots = @('app','resources\views','routes','database')
$pattern = '\xC3[\x80-\xBF]|\xC2[\x80-\xBF]|\xE2\x80[\x99\x9C\x9D\x93\x94\xA6]|\xE2\x82\xAC'
$hits = @()
foreach ($r in $roots) {
    if (-not (Test-Path $r)) { Write-Host "skip (missing): $r" -ForegroundColor DarkYellow; continue }
    Get-ChildItem -Path $r -Recurse -File -Include *.php,*.blade.php,*.json,*.md |
        ForEach-Object {
            $m = Select-String -Path $_.FullName -Pattern $pattern -AllMatches -Encoding UTF8
            if ($m) { $hits += [pscustomobject]@{ File = $_.FullName; Count = ($m.Matches | Measure-Object).Count } }
        }
}
if (-not $hits) { Write-Host "NO MOJIBAKE FOUND" -ForegroundColor Green; return }
$hits | Group-Object File |
    ForEach-Object { [pscustomobject]@{ Count = ($_.Group | Measure-Object Count -Sum).Sum; File = $_.Name } } |
    Sort-Object Count -Descending |
    Format-Table -AutoSize
