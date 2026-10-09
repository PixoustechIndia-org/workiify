import re

with open('app/views/pages/home.php', 'r', encoding='utf-8') as f:
    content = f.read()

# Revert premiumHighlight back to htmlspecialchars for anything that is NOT a _heading
content = re.sub(r"premiumHighlight\(\$data\['f'\]\['(?!.*?_heading)(.*?)'\]\)", r"htmlspecialchars($data['f']['\1'])", content)

with open('app/views/pages/home.php', 'w', encoding='utf-8') as f:
    f.write(content)

print("Done")
