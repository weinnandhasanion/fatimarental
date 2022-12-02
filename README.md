# Fatima Rental

### About this app

This is a simple management system I made for a local boarding house in Cebu. Technology stack for this project is simply HTML, CSS, jQuery, PHP, and MySQL for database management.

### Run the app (First-time setup)

1. Clone the repository
2. After cloning, put the folder inside C:\xampp\htdocs
3. Open XAMPP Control Panel (Download here https://downloadsapachefriends.global.ssl.fastly.net/7.4.29/xampp-windows-x64-7.4.29-1-VC15-installer.exe?from_af=true)
4. Start _Apache_ and _MySQL_
5. Go to browser and enter `localhost/phpmyadmin` in address bar
6. Once in phpMyAdmin dashboard, go to **Import** tab
7. Click _Choose file_ button and import _db.sql_ found in the root directory, then click _Go_
8. Once imported, open another tab and enter `localhost/fatimarental/admin` in the address bar
9. Enter default login details for admin

```
Username: admin
Password: 123
```

10. You are now logged in!

### Access Tenant Page

1. Add a new tenant in the Tenants page _(localhost/fatimarental/admin/tenants.php)_
2. Click _Update_ and generate a username
3. Copy the generated username and open another tab, then enter `localhost/fatimarental/tenant` in the address bar
4. Paste the copied username and enter **fatima123** as the default password
5. Verify login

## Future of this app

This project will be migrated to React Typescript and to utilize a REST API using Express in the near future.

### Developer Assignments

#### Oliver

Profile Details (Tenant)

```
tenant\profile.php
tenant\profile\profile_details.php
```

Bills (Tenant)

```
tenant\profile.php
tenant\profile\bills.php
```

Payment History (Tenant)

```
tenant\profile.php
tenant\profile\payments.php
```

#### Jay

Room History (Tenant)

```
tenant/profile.php
tenant/profile/history.php
```

Change Password (Tenant)

```
tenant/profile.php
tenant/profile/change-password.php
```

Dashboard

```
admin/pages/dashboard.php
```

Reservations

```
admin/pages/reservations.php
admin/functions/approve_reservation.php
admin/functions/update_reservation.php
admin/functions/reservation_details.php
```

#### Russell

Tenants

```
admin\pages\tenants.php
admin\functions\user_details.php
admin\functions\add_tenant.php
admin\functions\update_tenant.php
admin\functions\generate_username.php
admin\functions\disable_tenant.php
```

Records

```
admin\pages\records.php
```

Billing

```
admin\pages\billing.php
admin\functions\room_price.php
admin\functions\get_room_tenants.php
admin\functions\add_bill.php
shared\bill_details.php
```

#### JC

Rooms

```
admin\pages\rooms.php
admin\functions\room_details.php
admin\functions\add_room.php
admin\functions\update_room.php
admin\pages\edit_room_images.php
```

Payments

```
admin\pages\payments.php
admin\functions\get_payment_details.php
admin\functions\get_bill_details.php
admin\functions\add_payment.php
```

Income Reports

```
admin\pages\reports.php
admin\functions\get_reports.php
```

#### Pre-requisites

**Must familiarize:**

```
services\connect.php
services\login.php
services\logout.php
index.php
admin\functions\send_message.php
```

**CRON jobs:**

```
admin\jobs\check_bill.php
admin\jobs\check_reservation.php
```
