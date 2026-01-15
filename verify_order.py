#!/usr/bin/env python3
# -*- coding: utf-8 -*-
import re

html_file = r"C:\Users\LENOVO\Downloads\Velki Super Agent - ভেলকি অফিসিয়াল এজেন্ট লিস্টে.html"

with open(html_file, 'r', encoding='utf-8') as f:
    content = f.read()

# Find agents with their order in HTML
agents = re.findall(r'<h1 class="elementor-heading-title[^>]*>([^<]+)</h1>.*?ID\s*:\s*(\d+)', content, re.DOTALL)

print('Agent order in HTML:')
for i, (name, agent_id) in enumerate(agents, 1):
    if name != 'Velki Super Agent':
        print(f'{i}. {name:<20} - ID: {agent_id}')
