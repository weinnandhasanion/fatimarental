Must familiarize:
   services\connect.php
   services\login.php
   services\logout.php
   index.php
   admin\functions\send_message.php

CRON jobs:
	admin\jobs\check_bill.php
	admin\jobs\check_reservation.php

1. Oliver
   Profile Details (T)
		tenant\profile.php
		tenant\profile\profile_details.php
   Bills (T)
		tenant\profile.php
		tenant\profile\bills.php
		Payment History (T)
		tenant\profile.php
		tenant\profile\payments.php

2. Jay
   Room History (T)
		tenant\profile.php
		tenant\profile\history.php
   Change Password (T)
		tenant\profile.php
		tenant\profile\change-password.php
   Dashboard
		admin\pages\dashboard.php
   Reservations
		admin\pages\reservations.php
		admin\functions\approve_reservation.php
		admin\functions\update_reservation.php
		admin\functions\reservation_details.php

3. Russel
   Tenants
		admin\pages\tenants.php
		admin\functions\user_details.php
		admin\functions\add_tenant.php
		admin\functions\update_tenant.php
		admin\functions\generate_username.php
		admin\functions\disable_tenant.php
   Records
		admin\pages\records.php
   Billing
		admin\pages\billing.php
		admin\functions\room_price.php
		admin\functions\get_room_tenants.php
		admin\functions\add_bill.php
		shared\bill_details.php

4. JC
   Rooms
		admin\pages\rooms.php
		admin\functions\room_details.php
		admin\functions\add_room.php
		admin\functions\update_room.php
		admin\pages\edit_room_images.php
   Payments
		admin\pages\payments.php
		admin\functions\get_payment_details.php
		admin\functions\get_bill_details.php
		admin\functions\add_payment.php
   Income Reports
		admin\pages\reports.php
		admin\functions\get_reports.php
