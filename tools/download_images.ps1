$dest = "C:\xampp\htdocs\pothik\assets\images"
New-Item -ItemType Directory -Force $dest | Out-Null
$headers = @{ 'User-Agent' = 'PothikStudentProject/1.0 (university assignment)' }

$items = @(
    @{ file = 'ahsan-manzil';  q = 'Ahsan Manzil Dhaka' },
    @{ file = 'cox-bazar';     q = "Cox's Bazar sea beach" },
    @{ file = 'jaflong';       q = 'Jaflong Sylhet' },
    @{ file = 'mahasthangarh'; q = 'Mahasthangarh Bogra' },
    @{ file = 'paharpur';      q = 'Paharpur Buddhist Vihara' },
    @{ file = 'saint-martin';  q = "Saint Martin's Island Bangladesh" },
    @{ file = 'sajek-valley';  q = 'Sajek Valley' },
    @{ file = 'sreemangal';    q = 'Sreemangal tea garden' },
    @{ file = 'sundarbans';    q = 'Sundarbans mangrove forest' },
    @{ file = 'tanguar-haor';  q = 'Tanguar Haor' }
)

$credits = @("Image credits (Wikimedia Commons)", "")

foreach ($it in $items) {
    $query = [uri]::EscapeDataString($it.q + ' filetype:bitmap')
    $url = "https://commons.wikimedia.org/w/api.php?action=query&generator=search&gsrnamespace=6&gsrsearch=$query&gsrlimit=20&prop=imageinfo&iiprop=url|size|mime|extmetadata&iiurlwidth=1000&format=json"

    try {
        $r = Invoke-RestMethod -Uri $url -Headers $headers
    } catch {
        Write-Host "ERROR   $($it.file): $($_.Exception.Message)"
        continue
    }
    if (-not $r.query) { Write-Host "NO RESULT: $($it.file)"; continue }

    $pick = $r.query.pages.PSObject.Properties.Value |
        Where-Object {
            $ii = $_.imageinfo[0]
            $ii.mime -eq 'image/jpeg' -and $ii.width -ge 1000 -and $ii.width -gt $ii.height
        } |
        Sort-Object index | Select-Object -First 1

    if (-not $pick) { Write-Host "NO GOOD IMAGE: $($it.file)"; continue }

    $ii = $pick.imageinfo[0]
    $out = Join-Path $dest ($it.file + '.jpg')
    Invoke-WebRequest -Uri $ii.thumburl -Headers $headers -OutFile $out

    $artist = ''
    $license = ''
    try { $artist = ($ii.extmetadata.Artist.value -replace '<[^>]+>', '').Trim() } catch {}
    try { $license = $ii.extmetadata.LicenseShortName.value } catch {}

    $credits += "$($it.file).jpg | $($pick.title) | $artist | $license | $($ii.descriptionurl)"
    Write-Host "OK      $($it.file)  <-  $($pick.title)"
    Start-Sleep -Seconds 2
}

$credits | Set-Content -Path (Join-Path $dest 'CREDITS.txt') -Encoding UTF8
Write-Host "Done. See CREDITS.txt"