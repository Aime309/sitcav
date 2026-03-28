import requests
import json
import sys

BASE_URL = "http://127.0.0.1:5000"

def login(cedula, password):
    print(f"Logging in as {cedula}...")
    response = requests.post(f"{BASE_URL}/login", json={
        "cedula": cedula,
        "password": password
    })
    if response.status_code == 200:
        print("Login successful")
        return response.json()['user']
    else:
        print(f"Login failed: {response.text}")
        return None

def update_negocio_address(address):
    print(f"Updating business address to: {address}")
    response = requests.put(f"{BASE_URL}/api/negocio", json={
        "nombre": "Abastos Prueba",
        "rif": "J-123456789",
        "telefono": "0414-1234567",
        "direccion": address
    })
    if response.status_code == 200:
        print("Address updated successfully")
        return True
    else:
        print(f"Address update failed: {response.text}")
        return False

def create_venta(user_id):
    print("Creating a test sale...")
    # First get a product to sell
    products = requests.get(f"{BASE_URL}/api/productos").json()
    if not products:
        print("No products found to sell")
        return None
    
    product = products[0]
    
    venta_data = {
        "id_cliente": 1, # Assuming client 1 exists
        "id_vendedor": user_id,
        "metodo_pago": "divisas",
        "productos": [
            {
                "id": product['id'],
                "cantidad": 1,
                "precio": product['precio_unitario_dolares']
            }
        ]
    }
    
    response = requests.post(f"{BASE_URL}/api/ventas", json=venta_data)
    if response.status_code == 201:
        print("Sale created successfully")
        return response.json()
    else:
        print(f"Sale creation failed: {response.text}")
        return None

def create_reembolso(venta_id, user_id, monto):
    print(f"Creating refund for sale {venta_id}...")
    reembolso_data = {
        "id_venta": venta_id,
        "id_usuario": user_id,
        "monto_dolares": monto,
        "motivo": "Verification Test"
    }
    
    response = requests.post(f"{BASE_URL}/api/reembolsos", json=reembolso_data)
    if response.status_code == 201:
        print("Refund created successfully")
        return response.json()
    else:
        print(f"Refund creation failed: {response.text}")
        return None

def list_reembolsos():
    print("Listing refunds...")
    response = requests.get(f"{BASE_URL}/api/reembolsos")
    if response.status_code == 200:
        reembolsos = response.json()
        print(f"Found {len(reembolsos)} refunds")
        return reembolsos
    else:
        print(f"Failed to list refunds: {response.text}")
        return None

def main():
    # 1. Login as Encargado
    user = login("12345678", "test1")
    if not user:
        sys.exit(1)
        
    # 2. Update Address
    if not update_negocio_address("Calle Verificacion 123"):
        sys.exit(1)
        
    # 3. Create Sale
    venta_response = create_venta(user['id'])
    if not venta_response:
        sys.exit(1)
        
    venta = venta_response['venta']
    print(f"Sale ID: {venta['id']}")
    print(f"Historical Rate: {venta.get('cotizacion_dolar_bolivares')}")
    
    if 'cotizacion_dolar_bolivares' not in venta:
        print("ERROR: Historical rate not found in sale response")
    
    # 4. Create Refund
    reembolso_response = create_reembolso(venta['id'], user['id'], 5.00)
    if not reembolso_response:
        sys.exit(1)
        
    # 5. List Refunds
    reembolsos = list_reembolsos()
    found = False
    for r in reembolsos:
        if r['id'] == reembolso_response['reembolso']['id']:
            found = True
            print(f"Verified refund {r['id']} in list")
            break
            
    if not found:
        print("ERROR: Created refund not found in list")
        sys.exit(1)
        
    print("\n✅ VERIFICATION SUCCESSFUL")

if __name__ == "__main__":
    main()
