#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Super Agents HTML Parser v2
Improved extraction with better pattern matching
"""

import re
import csv

def extract_agents_from_html(filepath):
    """Extract all Super Agents using regex patterns"""
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()

    # Find all agent blocks (each starts with e-loop-item)
    agent_pattern = r'e-loop-item-(\d+).*?<h1 class="elementor-heading-title[^>]*>([^<]+)</h1>.*?ID\s*:\s*(\d+)(.*?)(?=e-loop-item-\d+|<footer|$)'

    agents = []

    for match in re.finditer(agent_pattern, content, re.DOTALL):
        loop_id = match.group(1)
        name = match.group(2).strip()
        agent_id = match.group(3).strip()
        section = match.group(4)

        # Skip page title
        if name == 'Velki Super Agent':
            continue

        # Extract WhatsApp numbers
        whatsapp_numbers = re.findall(r'wa\.me/\+?([0-9]+)', section)
        whatsapp_numbers = list(dict.fromkeys(whatsapp_numbers))  # Remove duplicates, keep order

        # Extract Facebook URL
        fb_match = re.search(r'facebook\.com/share/([^/"\s]+)', section)
        facebook_id = fb_match.group(1) if fb_match else ''

        # Check for image (not placeholder)
        img_match = re.search(r'wp-content/uploads/2025/10/([^.\s"]+\.(?:webp|jpg|png))', section)
        image_filename = ''
        if img_match and 'placeholder' not in img_match.group(1):
            image_filename = img_match.group(1)

        agent = {
            'name': name,
            'id': agent_id,
            'whatsapp_numbers': whatsapp_numbers,
            'facebook_id': facebook_id,
            'image_filename': image_filename
        }

        agents.append(agent)

    return agents

def create_csv(agents, output_filepath):
    """Create CSV file with all Super Agents data"""
    # Sort agents by ID
    agents_sorted = sorted(agents, key=lambda x: int(x.get('id', '999')))

    with open(output_filepath, 'w', newline='', encoding='utf-8-sig') as csvfile:
        fieldnames = [
            'Agent_Number',
            'Agent_Name',
            'Agent_ID',
            'Image_URL',
            'WhatsApp_International',
            'WhatsApp_Bangladesh',
            'Facebook_URL'
        ]

        writer = csv.DictWriter(csvfile, fieldnames=fieldnames)
        writer.writeheader()

        for idx, agent in enumerate(agents_sorted, 1):
            # Get WhatsApp numbers (first is international, second is Bangladesh if exists)
            whatsapp_numbers = agent.get('whatsapp_numbers', [])
            whatsapp_int = whatsapp_numbers[0] if len(whatsapp_numbers) > 0 else ''
            whatsapp_bd = whatsapp_numbers[1] if len(whatsapp_numbers) > 1 else whatsapp_int

            # Build complete URLs
            image_url = ''
            if agent.get('image_filename'):
                image_url = f"https://velkiagents.vip/wp-content/uploads/2025/10/{agent['image_filename']}"

            whatsapp_int_url = f"https://wa.me/+{whatsapp_int}" if whatsapp_int else ''
            whatsapp_bd_url = f"https://wa.me/+{whatsapp_bd}" if whatsapp_bd else ''

            facebook_url = ''
            if agent.get('facebook_id'):
                facebook_url = f"https://www.facebook.com/share/{agent['facebook_id']}/"

            writer.writerow({
                'Agent_Number': idx,
                'Agent_Name': agent.get('name', ''),
                'Agent_ID': agent.get('id', ''),
                'Image_URL': image_url,
                'WhatsApp_International': whatsapp_int_url,
                'WhatsApp_Bangladesh': whatsapp_bd_url,
                'Facebook_URL': facebook_url
            })

    return len(agents_sorted)

if __name__ == '__main__':
    # File paths
    html_file = r"C:\Users\LENOVO\Downloads\Velki Super Agent - ভেলকি অফিসিয়াল এজেন্ট লিস্টে.html"
    csv_file = r"C:\xampp\htdocs\velki\super_agents_complete.csv"

    print("Parsing HTML file...")
    agents = extract_agents_from_html(html_file)

    print(f"Found {len(agents)} Super Agents")

    # Display agent summary
    for agent in sorted(agents, key=lambda x: int(x.get('id', '999'))):
        wa_count = len(agent.get('whatsapp_numbers', []))
        print(f"  ID {agent['id']:>3}: {agent['name']:<20} - WhatsApp: {wa_count} numbers")

    print("\nCreating CSV file...")
    count = create_csv(agents, csv_file)

    print(f"[OK] Successfully created CSV with {count} Super Agents")
    print(f"[OK] Saved to: {csv_file}")
