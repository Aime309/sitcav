import os

def add_ids():
    file_path = 'c:/Users/nadet/Desktop/Proyecto/index.html'
    
    with open(file_path, 'r', encoding='utf-8') as f:
        content = f.read()
    
    # Replacements - Adjusted based on latest file view
    replacements = [
        (
            '<li class="nav-item">\n                    <a class="nav-link" onclick="showSection(\'productos\')">',
            '<li class="nav-item" id="nav-productos">\n                    <a class="nav-link" onclick="showSection(\'productos\')">'
        ),
        (
            '<li class="nav-item">\n                    <a class="nav-link" onclick="showSection(\'clientes\')">',
            '<li class="nav-item" id="nav-clientes">\n                    <a class="nav-link" onclick="showSection(\'clientes\')">'
        ),
        (
            '<li class="nav-item">\n                    <a class="nav-link" onclick="showSection(\'ventas\')">',
            '<li class="nav-item" id="nav-ventas">\n                    <a class="nav-link" onclick="showSection(\'ventas\')">'
        )
    ]
    
    new_content = content
    changes_made = False
    for target, replacement in replacements:
        if target in new_content:
            new_content = new_content.replace(target, replacement)
            print(f"Replaced: {target.strip()[:30]}...")
            changes_made = True
        else:
            print(f"Target not found: {target.strip()[:30]}...")
            
    if changes_made:
        with open(file_path, 'w', encoding='utf-8') as f:
            f.write(new_content)
        print("File updated successfully.")
    else:
        print("No changes made.")

if __name__ == "__main__":
    add_ids()
