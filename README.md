
# 🧠 AI Operator Bridge for Laravel Systems

![License](https://img.shields.io/badge/license-MIT-blue.svg)  
![Built With](https://img.shields.io/badge/built%20with-PHP%207.4%2B-informational)  
![Laravel Friendly](https://img.shields.io/badge/compatible-Laravel%205%2B-orange)  
![Status](https://img.shields.io/badge/status-Stable-brightgreen)

---

## 🧩 Overview

This **PHP Operator Bridge** allows external AI agents or automation clients to **control a Laravel-based application** without touching any core files.  
All operations—from user management to order processing—are exposed via **secure, standalone endpoints** that directly query your Laravel database.

### Key Features

- Zero modifications to your Laravel codebase  
- Token- or header-based authentication  
- Full CRUD on Users, Stores, Items, Carts, Orders, Transactions, Wallets, Zones, Notifications, Admin Roles  
- Health checks, Webhook receiver, Admin dashboard, Logging  
- Easily extendable for any additional table or feature  


---

## 🚀 Installation

1. **Clone** or **download** this repo into your hosting:  
```

[https://yourdomain.com/ai\_operator/](https://yourdomain.com/ai_operator/)

````
2. **Configure** `config.php`:  
- `ACCESS_KEY` → a strong, secret token  
- `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS` → your Laravel database credentials  
3. **Secure** the directory (outside your Laravel root, enforce HTTPS, IP-whitelisting, etc.)  
4. **Invoke** endpoints via `GET` or `POST` with `?key=YOUR_ACCESS_KEY` or `X-Access-Key` header  

---

## 🔧 Architecture Diagram

```mermaid
flowchart LR
 A[AI Agent / Bot] -->|HTTPS & Auth| B[Operator Bridge Endpoints<br>PHP scripts]
 B --> C[MySQL Database<br>Laravel Schema]
 B --> D[Access.log<br>via logger.php]
 B --> E[Webhook Receiver<br>webhook_receiver.php]
 B --> F[Health Check<br>health_check.php]
 B --> G[Admin Dashboard<br>admin.php]
````

* **A**: Any AI client (GPT, custom bot, Node.js, Python)
* **B**: Collection of PHP endpoint scripts
* **C**: Direct read/write to Laravel’s DB
* **D**: Immutable append-only log for all actions
* **E**/**F**/**G**: Monitoring, alerts, visual dashboard

---

## 🔧 Example Endpoints

All calls require `?key=YOUR_ACCESS_KEY` (or header `X-Access-Key: YOUR_ACCESS_KEY`).

| Action                   | Method | Endpoint & Payload                                                                                                                                                                               |
| ------------------------ | ------ | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ |
| System Ping              | GET    | `/system_ping.php?key=YOUR_ACCESS_KEY`                                                                                                                                                           |
| Get User by Email        | GET    | `/get_user_by_email.php?email=foo@bar.com&key=YOUR_ACCESS_KEY`                                                                                                                                   |
| Create User              | GET    | `/create_user.php?name=John&email=john@bar.com&password=secret&key=YOUR_ACCESS_KEY`                                                                                                              |
| Get Today’s Orders       | GET    | `/get_orders.php?key=YOUR_ACCESS_KEY`                                                                                                                                                            |
| Create Order             | POST   | `/create_order.php?key=YOUR_ACCESS_KEY`<br>Body JSON:<br>`json<br>{ "user_id":1, "items":[{"item_id":10,"price":9.99,"quantity":2}], "delivery_address":"123 Main St","payment_method":"card" }` |
| Set Order Status         | GET    | `/set_order_status.php?order_id=10&status=shipped&key=YOUR_ACCESS_KEY`                                                                                                                           |
| Update Order (Webhook)   | POST   | `/update_order_status.php?key=YOUR_ACCESS_KEY`<br>Body JSON:<br>`json<br>{ "order_id":10, "status":"delivered" }`                                                                                |
| Cancel Order             | GET    | `/cancel_order.php?order_id=10&reason=customer_request&key=YOUR_ACCESS_KEY`                                                                                                                      |
| Approve Vendor           | GET    | `/approve_vendor.php?vendor_id=123&key=YOUR_ACCESS_KEY`                                                                                                                                          |
| Get Stores               | GET    | `/get_stores.php?key=YOUR_ACCESS_KEY`                                                                                                                                                            |
| Get Store Detail         | GET    | `/get_store.php?id=5&key=YOUR_ACCESS_KEY`                                                                                                                                                        |
| Update Store Config      | POST   | `/update_store_config.php?key=YOUR_ACCESS_KEY`<br>Body JSON:<br>`json<br>{ "store_id":5, "config":{ "status":1, "footer_text":"Welcome!" } }`                                                    |
| Get Categories           | GET    | `/get_categories.php?key=YOUR_ACCESS_KEY`                                                                                                                                                        |
| Get Items                | GET    | `/get_items.php?module_id=2&store_id=5&key=YOUR_ACCESS_KEY`                                                                                                                                      |
| Get Item Detail          | GET    | `/get_item.php?id=42&key=YOUR_ACCESS_KEY`                                                                                                                                                        |
| Create Item              | POST   | `/create_item.php?key=YOUR_ACCESS_KEY`<br>Body JSON:<br>`json<br>{ "name":"Widget","price":12.5,"category_id":3,"store_id":5,"module_id":2 }`                                                    |
| Update Item              | POST   | `/update_item.php?key=YOUR_ACCESS_KEY`<br>Body JSON:<br>`json<br>{ "id":42, "price":14.0, "status":1 }`                                                                                          |
| Get Cart                 | GET    | `/get_cart.php?user_id=7&key=YOUR_ACCESS_KEY`                                                                                                                                                    |
| Add to Cart              | GET    | `/add_to_cart.php?user_id=7&item_id=42&quantity=3&key=YOUR_ACCESS_KEY`                                                                                                                           |
| Remove from Cart         | GET    | `/remove_from_cart.php?cart_id=15&key=YOUR_ACCESS_KEY`                                                                                                                                           |
| Get Account Transactions | GET    | `/get_account_transactions.php?from_type=user&from_id=7&key=YOUR_ACCESS_KEY`                                                                                                                     |
| Create Transaction       | POST   | `/create_account_transaction.php?key=YOUR_ACCESS_KEY`<br>Body JSON:<br>`json<br>{ "from_type":"user","from_id":7,"amount":50.00,"method":"stripe","type":"collected" }`                          |
| Get Wallet Balance       | GET    | `/get_wallet_balance.php?user_id=7&key=YOUR_ACCESS_KEY`                                                                                                                                          |
| Create Withdraw Request  | POST   | `/create_withdraw_request.php?key=YOUR_ACCESS_KEY`<br>Body JSON:<br>`json<br>{ "vendor_id":5,"amount":100.00,"withdrawal_method_id":2 }`                                                         |
| Get Zones                | GET    | `/get_zones.php?key=YOUR_ACCESS_KEY`                                                                                                                                                             |
| Get Zone Detail          | GET    | `/get_zone.php?id=1&key=YOUR_ACCESS_KEY`                                                                                                                                                         |
| Get Automated Messages   | GET    | `/get_automated_messages.php?key=YOUR_ACCESS_KEY`                                                                                                                                                |
| Send Notification        | POST   | `/send_notification.php?key=YOUR_ACCESS_KEY`<br>Body JSON:<br>`json<br>{ "user_id":7,"title":"Alert","description":"Your order shipped" }`                                                       |
| Get Admin Roles          | GET    | `/get_admin_roles.php?key=YOUR_ACCESS_KEY`                                                                                                                                                       |
| Assign Admin Role        | GET    | `/assign_admin_role.php?admin_id=2&role_id=4&key=YOUR_ACCESS_KEY`                                                                                                                                |
| Health Check             | GET    | `/health_check.php?key=YOUR_ACCESS_KEY`                                                                                                                                                          |
| Webhook Receiver         | POST   | `/webhook_receiver.php?key=YOUR_ACCESS_KEY`<br>Body JSON:<br>Laravel event payload                                                                                                               |
| Admin Dashboard          | GET    | `/admin.php?key=YOUR_ACCESS_KEY`                                                                                                                                                                 |

*(…and more—see **Included Files** for the full list.)*
                         |

---

## 🛡️ Security Recommendations

* **HTTPS only**: Reject any non-TLS request
* **Strong token**: Rotate `ACCESS_KEY` periodically
* **Network controls**: IP whitelisting, VPN-only, WAF rules
* **Isolated folder**: Keep this bridge outside of your main Laravel code

---

## 📁 Included Files

| File                             | Purpose                                            |
| -------------------------------- | -------------------------------------------------- |
| `config.php`                     | Secure key + DB connection + HTTPS enforcement     |
| `auth.php`                       | Header-based token auth helper                     |
| `logger.php`                     | Append-only JSON log (`access.log`)                |
| `index.php`                      | Animated landing page / status panel               |
| `system_ping.php`                | Health check endpoint                              |
| `health_check.php`               | Cron-driven DB/table monitoring + email alerts     |
| `webhook_receiver.php`           | Accept external events from Laravel, log + forward |
| `admin.php`                      | Web dashboard for recent logs                      |
| `get_user_by_email.php`          | User lookup                                        |
| `create_user.php`                | User creation                                      |
| `get_orders.php`                 | Today’s orders                                     |
| `create_order.php`               | New order + line items                             |
| `set_order_status.php`           | Simple order status update via GET                 |
| `update_order_status.php`        | Complex order update via POST (webhook)            |
| `cancel_order.php`               | Order cancellation                                 |
| `approve_vendor.php`             | Vendor approval                                    |
| `get_stores.php`                 | List all stores                                    |
| `get_store.php`                  | Store detail                                       |
| `update_store_config.php`        | Partial update on store settings via POST          |
| `get_categories.php`             | List categories                                    |
| `get_items.php`                  | Filterable product list                            |
| `get_item.php`                   | Single product detail                              |
| `create_item.php`                | Product creation via POST                          |
| `update_item.php`                | Product update via POST                            |
| `get_cart.php`                   | User’s cart contents                               |
| `add_to_cart.php`                | Add an item via GET                                |
| `remove_from_cart.php`           | Remove an item via GET                             |
| `get_account_transactions.php`   | Transaction history                                |
| `create_account_transaction.php` | New transaction via POST                           |
| `get_wallet_balance.php`         | Wallet balance lookup                              |
| `create_withdraw_request.php`    | Withdrawal request via POST                        |
| `get_zones.php`                  | Zone lookup                                        |
| `get_zone.php`                   | Zone detail                                        |
| `get_automated_messages.php`     | Preconfigured bot messages                         |
| `send_notification.php`          | Push a notification into user\_inbox               |
| `get_admin_roles.php`            | Admin roles list                                   |
| `assign_admin_role.php`          | Assign role via GET                                |
| `.htaccess`                      | URL restriction + HTTPS redirect + hide sensitive  |
| `LICENSE`                        | MIT License                                        |

---


## 🔧 Cron setup example (on your server crontab):


```
*/5 * * * * php /path/to/ai_operator/health_check.php > /dev/null 2>&1
```

## 🤖 Integration Ideas

Pair this bridge with:

* **LLM Agents**: GPT-4, Claude, CrewAI
* **Automation scripts**: Python, Node.js
* **Messaging bots**: WhatsApp, Telegram
* **Cron jobs**: Periodic health checks, data sync

> **You maintain Laravel**. **Your AI agents manage it**.

---

## 📜 License

MIT License © 2025 **Andryu Schittone – Malios Dark**
Use, modify, distribute—no warranty implied.


