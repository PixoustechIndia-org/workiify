import re

with open('app/views/pages/home.php', 'r', encoding='utf-8') as f:
    content = f.read()

content = re.sub(r"htmlspecialchars\(\$data\['f'\]\['(.*?)'\]\)", r"premiumHighlight($data['f']['\1'])", content)

with open('app/views/pages/home.php', 'w', encoding='utf-8') as f:
    f.write(content)

print("Done")
