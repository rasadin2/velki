#!/usr/bin/env python3
# -*- coding: utf-8 -*-
import re
import csv

html_file = r"C:\Users\LENOVO\Downloads\Velki Super Agent - ভেলকি অফিসিয়াল এজেন্ট লিস্টে.html"
csv_file = r"C:\xampp\htdocs\velki\super_agents_complete.csv"

# Read CSV
with open(csv_file, 'r', encoding='utf-8-sig') as f:
    reader = csv.DictReader(f)
    csv_agents = list(reader)

# Read HTML
with open(html_file, 'r', encoding='utf-8') as f:
    content = f.read()

print("Verifying data accuracy...\n")

# Check 3 sample agents
test_agents = [
    ('Abir Chowdhury', '004'),
    ('Hasan vai', '001'),
    ('Jishan Ahmed', '30')
]

for name, agent_id in test_agents:
    # Find in HTML
    pattern = rf'{re.escape(name)}.*?ID\s*:\s*{agent_id}(.*?)(?=<h1 class="elementor-heading-title|<footer)'
    match = re.search(pattern, content, re.DOTALL)

    if match:
        section = match.group(1)
        wa_numbers = re.findall(r'wa\.me/\+?([0-9]+)', section)
        wa_numbers = list(dict.fromkeys(wa_numbers))  # Remove duplicates

        # Find in CSV
        csv_agent = next((a for a in csv_agents if a['Agent_ID'] == agent_id), None)

        print(f"{name} (ID: {agent_id})")
        print(f"  HTML WhatsApp: {wa_numbers}")
        if csv_agent:
            csv_wa = csv_agent['WhatsApp_International'].replace('https://wa.me/+', '')
            print(f"  CSV WhatsApp:  {csv_wa}")
            print(f"  Match: {'YES' if csv_wa in wa_numbers else 'NO'}")
        print()
