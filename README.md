 Laravel Payment System named order-payment-api

---

### 📘 Why You Should Read This README

The `README.md` file is your **complete guide to understanding, setting up, and using this project effectively**. It contains critical information such as:

* ✅ **Installation & Environment Setup** — So you can get the project running locally without guesswork.
* 🔐 **Authentication Flow** — Learn how to securely register, log in, and use JWT tokens to access protected endpoints.
* 💳 **Order & Payment API Usage** — Get step-by-step instructions for working with orders, processing payments, and handling gateway callbacks.
* 🧩 **Payment Gateway Extensibility** — Understand how the system is designed using the Strategy Pattern and how to add new payment gateways with minimal effort.
* 🧪 **Testing & Seeding** — Learn how to run tests and use seeders/factories to populate the database with sample data for testing.
* 📂 **Postman Documentation** — Quickly test endpoints with the included collection and understand request/response formats.

> 🚨 Whether you're a developer, tester, or reviewer — reading the README ensures you avoid misconfiguration, know the business rules, and understand the architecture.

**TL;DR: The README saves your time, prevents confusion, and helps you get the most out of the system.**

---


## 📦 Overview
This project is a modular HMVC Laravel 12 API system to manage orders and payments using an extensible, strategy-based architecture for payment gateways (Stripe, PayPal, etc.).
------------------
##APIs Only
support Api versioning (this is version one) so all Apis prifixed like this api/v1
----------

## 📦 PostMan Collection 
•	Postman collection and API documentation shared URL ::  https://documenter.getpostman.com/view/30436383/2sB2x6nsdE 
* dont forget to set your Postman workspace env (url and token)
* You can import it in Postman to test:
  Auth ,
  Orders and 
  Payments
--------

## 📦 Routes
## Route list :: 18 routes
  * POST            api/v1/login.
  * POST            api/v1/logout .
  * GET|HEAD        api/v1/me .
  * GET|HEAD        api/v1/orders .
  * POST            api/v1/orders 
  * GET|HEAD        api/v1/orders/{order} .
  * PUT|PATCH       api/v1/orders/{order} .
  * DELETE          api/v1/orders/{order} .
  * POST            api/v1/orders/{order} .
  * POST            api/v1/orders/{order}/confirm .
  * GET|HEAD        api/v1/payment/all .
  * GET|HEAD        api/v1/payment/callback/{gateway}.
  * GET|HEAD        api/v1/payment/pay/{gateway} .
  * POST            api/v1/refresh .
  * POST            api/v1/register .
-----------------


## 🚀 Setup Instructions

1. Clone Repo

```bash
git clone https://github.com/ammar422/order-payment-api.git
cd order-payment-api.git
```

2. Install Dependencies

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan jwt:secret
```
3.  Configure .env

* DB_CONNECTION=mysql
* DB_HOST=127.0.0.1
* DB_PORT=3306
* DB_DATABASE=order-payment-api
* DB_USERNAME=root
* DB_PASSWORD=


* JWT_TTL=60


* PAYPAL_MODE=sandbox
* PAYPAL_SANDBOX_CLIENT_ID=
* PAYPAL_SANDBOX_CLIENT_SECRET=

* PAYPAL_LIVE_CLIENT_ID=
* PAYPAL_LIVE_CLIENT_SECRET=

* PAYPAL_PAYMENT_ACTION=Sale
* PAYPAL_CURRENCY=MYR
* PAYPAL_NOTIFY_URL=https://yourdomain.com/paypal/notify
* PAYPAL_LOCALE=en_US
* PAYPAL_VALIDATE_SSL=true

* STRIPE_API_KEY=
* STRIPE_3D_ENABLED=true


4.Run Migrations & Seeders
* php artisan migrate:fresh --seed

5. serve
```bash
php artisan serve
```

Authentication
* JWT is used for securing routes.

* Endpoints:
* POST /api/v1/register
* POST /api/v1/login
* Use the returned token to access authenticated routes with:
* Authorization: Bearer {token}




---------------------------------------------------------

🧠 Payment Gateway Extensibility
✨ How to Add a New Gateway

1.Implement the Interface
* class NewGateway implements PaymentGatewayInterface
* Implement pay() and handleCallback()

2.Register It
* In PaymentGatewayResolver.php:
*
  ```
  return match ($gatewayName) {
    'stripe' => new StripeGateway(),
    'paypal' => new PayPalGateway(),
    'newgateway' => new NewGateway(),
    default => throw ...

};

3.define your new payment class as you like (3rd party package or manually) and set up your logic inside and here we go 

4.set your config (config/newGateway.php) and you env vars
* That’s it. Your gateway is now available at:

5.payment process routes
* /payment/pay/newgateway
* /payment/callback/newgateway


----------------------------------------------------
📬 Notes & Assumptions
* Each order can have multiple payments.

* Order updates and deletion is only allowed when no payments exist.

* Payments only allowed for orders with status = confirmed.

* Uses UUIDs and SoftDeletes for all core models.
  
* Uses Modules for Orders and Payments for scalability.

* Initialized lynx package for unify the API response Globally.

----------------------



