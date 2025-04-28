# Arkit

[![PHP Version](https://img.shields.io/badge/PHP-7.4%2B-blue.svg)](https://www.php.net/releases/7_4_0.php)  
[![Composer](https://img.shields.io/badge/Composer-required-brightgreen.svg)](https://getcomposer.org/)  
[![Framework Type](https://img.shields.io/badge/Framework-Lightweight-informational.svg)](#)  
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](LICENSE)  
[![Platform](https://img.shields.io/badge/Platform-Linux%20%7C%20Windows-lightgrey.svg)](#)  
[![Documentation](https://img.shields.io/badge/Docs-Available-brightgreen.svg)](https://ymlee0919.github.io/arkit/)

> A lightweight and flexible PHP framework to build modular web applications sharing a unified business core.

[Explore Documentation](https://ymlee0919.github.io/arkit/)  
[View the Source Code](https://github.com/ymlee0919/arkit)

# *What is Arkit?*

Arkit is a platform for developing web applications in PHP. I do not consider it to be a framework since third-party libraries are used for its operation and it only has the basics to develop a web application.

It is designed so that several web applications run on the same platform and all of them share the same business model.
Let's say you have a company that offers a certain service. On the same platform you can set up the main page of the business, an administration panel, a panel for the client, a panel for your workers (independent of the administration) and an API to be consumed by a mobile application. All this in a single system, a single business logic, a single database.

It is also possible to have as many business models as you need, each business model with its independent database and database management system.

It has been the work of several years trying to improve a generic tool to develop management applications on the web.

## Key Features

- **Multi-system Architecture:** Manage different applications from a common business core.
- **Custom Routing:** Support for domain-based and URL-based routes.
- **Built-in Caching:** Improve performance with cache support.
- **Session Management:** Easy session handling across systems.
- **Security Helpers:** Input validation, CSRF protection, and sanitization utilities.
- **Database Handling:** Simplified database connections and queries.
- **Composer Integration:** Manage third-party libraries easily.

## Basic structure

Let's talk about the directory structure in a very general way.
 - **Arkit**: core of the platform.
 - **Model**: business model(s).
 - **Systems**: contains a subdirectory for each web application and it is these subdirectories that contain the implementation.
 - **resources**: resources such as cache files, compiled templates, system logs, etc.
 - **vendor**: third-party dependencies.
 - **index.php**: is the only entry point of the application.
 - **composer.json**: definition of third-party dependencies.

## Routing

The platform has two routing systems, one for domains and another for URLs. The domain's routing system defines the web application that handles the request. Once the platform determines the web application that should respond, said application uses URL routing to determine the controller that ultimately handles the request.


## Classes

Arkit has the basics to develop web applications.
It has a coupled class system for:
 - Manipulate requests
 - Manipulate environment variables
 - Form validation
 - Manipulate sending responses to the client using json formatting, redirection and html code using a templating engine (Smarty)
 - Log recording
 - Error handling
 - Manipulate cache (Apc, Memcache, Memcached and file)
 - Manipulate cookies
 - Manipulate session
 - Manage data access
 - Manage access control
 - Work with cryptography
 - Manage routes, at the domain and URL level.
 - Define request handlers

It proposes a way to organize and structure the code.

You can see the [API Dococumentation](https://ymlee0919.github.io/arkit/ "API Documentation")

## Installation & Setup

### Requirements

- PHP 7.4+
- Composer
- Web server (Apache, Nginx, etc.)

### Quick Start

1. **Clone the repository**

```bash
git clone https://github.com/ymlee0919/arkit.git
cd arkit
```

2. **Install Composer dependencies**

```bash
composer install
```

3. **Configure your web server**  
   Set the **public** folder as your document root.

4. **Create and configure your systems**
   - Build your apps inside `/System`.
   - Define your routes and controllers.

<br>

---

<br>
<br>

If you want to know a little more, you can contact me at: [ymlee0919@gmail.com](mailto:ymlee0919@gmail.com "ymlee0919@gmail.com")
