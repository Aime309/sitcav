# Tabla de Refactorización de View Functions y Endpoints

| Nombre actual              | Sugerencia nueva           | Observaciones                                 |
|----------------------------|----------------------------|-----------------------------------------------|
| search_clientes            | filter_clients             |                                               |
| search_productos           | filter_products            |                                               |
| productos_stock_bajo       | select_low_stock_products  |                                               |
| get_venta                  | select_sale                |                                               |
| get_compra_pdf             | select_purchase_pdf        |                                               |
| get_compra                 | select_purchase            |                                               |
| get_apartado               | select_layaway             |                                               |
| completar_apartado         | complete_layaway           |                                               |
| cancelar_apartado          | cancel_layaway             |                                               |
| generar_pdf_apartado       | select_layaway_pdf         |                                               |
| list_pagos_venta           | select_payments            |                                               |
| upload_usuario_foto        | update_user_photo          |                                               |
| list_empleados             | select_employees           |                                               |
| delete_empleado            | delete_employee            |                                               |
| ajuste_inventario          | update_inventory           |                                               |
| get_dashboard_stats        | select_dashboard_stats     |                                               |
| get_estadisticas_resumen   | select_statistics_summary  |                                               |
| get_estadisticas_historico | select_statistics_history  |                                               |
| get_estadisticas           | select_statistics_reports  |                                               |
| reporte_ventas             | select_sales_report        |                                               |
| generar_reporte_ventas_pdf_endpoint | select_sales_report_pdf |                                      |
| generar_factura            | select_invoice             |                                               |
| get_reembolso_pdf          | select_refund_pdf          |                                               |
| crear_backup               | create_file_backup         | Respaldo físico (archivo .db)                 |
| create_backup              | create_sql_backup          | Respaldo SQL (dump .sql)                      |
| list_uploaded_files        | select_uploaded_files      |                                               |
| uploaded_file              | select_uploaded_file       |                                               |
| serve_profile_photo        | select_user_photo          |                                               |
| login_options              | login_options              |                                               |
| login                      | login                      |                                               |
| logout                     | logout                     |                                               |
| register                   | insert_user                |                                               |
| check_user_recovery        | check_user_recovery        |                                               |
| verify_security_answers    | check_security_answers     |                                               |
| reset_password_recovery    | update_user_password       |                                               |
