import http.client
import re

# Step 1: Fetch HTML content from the URL
def fetch_html(url):
    conn = http.client.HTTPSConnection("www.php.net")
    conn.request("GET", url)
    response = conn.getresponse()
    html_content = response.read().decode('utf-8')
    conn.close()
    return html_content

# Step 2: Extract and filter the content
def extract_functions(html_content):
    # Split content into lines
    lines = html_content.splitlines()
    
    # Use regex to find lines containing the word 'function'
    filtered_lines = [line for line in lines if re.search(r'\bfunction\b', line, re.IGNORECASE)]
    return filtered_lines

# Step 3: Write the filtered lines to a text file
def write_to_file(filename, lines):
    with open(filename, 'w') as file:
        for line in lines:
            file.write(line + '\n')

# Main workflow
url = '/manual/en/indexes.functions.php'
html_content = fetch_html(url)
filtered_lines = extract_functions(html_content)
write_to_file('functions.txt', filtered_lines)

print("Filtered lines have been written to 'functions.txt'")
