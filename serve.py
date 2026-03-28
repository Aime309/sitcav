"""
Servidor HTTP simple para servir el frontend
Esto evita problemas de CORS al abrir index.html directamente
"""
import http.server
import socketserver

PORT = 8000

Handler = http.server.SimpleHTTPRequestHandler

with socketserver.TCPServer(("", PORT), Handler) as httpd:
    print(f"✅ Servidor HTTP corriendo en http://localhost:{PORT}")
    print(f"📂 Abre tu navegador en: http://localhost:{PORT}/index.html")
    print("⚠️  Presiona Ctrl+C para detener")
    httpd.serve_forever()
