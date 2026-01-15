#!/usr/bin/env python3
# -*- coding: utf-8 -*-
import re

html_file = r"C:\Users\LENOVO\Downloads\Velki Super Agent - ভেলকি অফিসিয়াল এজেন্ট লিস্টে.html"

with open(html_file, 'r', encoding='utf-8') as f:
    content = f.read()

# Find all agent names
names = re.findall(r'<h1 class="elementor-heading-title[^>]*>([^<]+)</h1>', content)
agent_names = [name for name in names if name != 'Velki Super Agent']

print('Agent names found:')
for i, name in enumerate(agent_names, 1):
    print(f'{i}. {name}')

print(f'\nTotal agents: {len(agent_names)}')

# Check the last agent
print('\n--- Checking last agent details ---')
last_match = re.search(r'ID\s*:\s*(\d+).*?wa\.me/\+?([0-9]+)', content, re.DOTALL)
if last_match:
    print(f'Last agent ID: {last_match.group(1)}')
    print(f'Last WhatsApp number: +{last_match.group(2)}')
