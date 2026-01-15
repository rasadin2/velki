#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Super Agents HTML Parser
Extracts all Super Agent data from HTML file and creates a comprehensive CSV
"""

import re
import csv
from html.parser import HTMLParser

class SuperAgentParser(HTMLParser):
    def __init__(self):
        super().__init__()
        self.agents = []
        self.current_agent = {}
        self.in_heading = False
        self.heading_type = None
        self.current_data = []

    def handle_starttag(self, tag, attrs):
        attrs_dict = dict(attrs)

        # Agent name (h1 with class elementor-heading-title)
        if tag == 'h1' and 'class' in attrs_dict and 'elementor-heading-title' in attrs_dict['class']:
            self.in_heading = True
            self.heading_type = 'name'

        # Agent ID (h3 with ID pattern)
        elif tag == 'h3' and 'class' in attrs_dict and 'elementor-heading-title' in attrs_dict['class']:
            self.in_heading = True
            self.heading_type = 'id'

        # WhatsApp links
        elif tag == 'a' and 'href' in attrs_dict:
            href = attrs_dict['href']
            if 'wa.me/' in href:
                # Extract number from wa.me link
                match = re.search(r'wa\.me/([+\d]+)', href)
                if match:
                    number = match.group(1)
                    if 'whatsapp_numbers' not in self.current_agent:
                        self.current_agent['whatsapp_numbers'] = []
                    self.current_agent['whatsapp_numbers'].append(number)

            # Facebook share links
            elif 'facebook.com/share/' in href:
                match = re.search(r'facebook\.com/share/([^/]+)', href)
                if match:
                    self.current_agent['facebook_id'] = match.group(1)

        # Image source
        elif tag == 'img' and 'src' in attrs_dict:
            src = attrs_dict['src']
            # Check if it's not the placeholder image
            if 'velki-placeholder-image' not in src and 'wp-content/uploads' in src:
                # Extract filename from srcset if available
                if 'srcset' in attrs_dict:
                    srcset = attrs_dict['srcset']
                    match = re.search(r'wp-content/uploads/2025/10/([^\s]+\.webp)', srcset)
                    if match:
                        self.current_agent['image_filename'] = match.group(1)

    def handle_data(self, data):
        data = data.strip()
        if not data:
            return

        if self.in_heading:
            if self.heading_type == 'name' and data != '(Supper Agent)':
                self.current_agent['name'] = data
            elif self.heading_type == 'id' and data.startswith('ID :'):
                id_value = data.replace('ID :', '').strip()
                self.current_agent['id'] = id_value
                # Save current agent when we get the ID (end of agent block)
                if 'name' in self.current_agent:
                    self.agents.append(self.current_agent.copy())
                    self.current_agent = {}

    def handle_endtag(self, tag):
        if tag in ['h1', 'h3']:
            self.in_heading = False
            self.heading_type = None

def parse_html_file(filepath):
    """Parse the HTML file and extract all Super Agents"""
    with open(filepath, 'r', encoding='utf-8') as f:
        html_content = f.read()

    parser = SuperAgentParser()
    parser.feed(html_content)

    return parser.agents

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
            if 'image_filename' in agent:
                image_url = f"https://velkiagents.vip/wp-content/uploads/2025/10/{agent['image_filename']}"

            whatsapp_int_url = f"https://wa.me/{whatsapp_int}" if whatsapp_int else ''
            whatsapp_bd_url = f"https://wa.me/{whatsapp_bd}" if whatsapp_bd else ''

            facebook_url = ''
            if 'facebook_id' in agent:
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
    agents = parse_html_file(html_file)

    print(f"Found {len(agents)} Super Agents")

    print("Creating CSV file...")
    count = create_csv(agents, csv_file)

    print(f"[OK] Successfully created CSV with {count} Super Agents")
    print(f"[OK] Saved to: {csv_file}")
