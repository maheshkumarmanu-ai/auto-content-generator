# WordPress Auto Content Generator

An advanced, enterprise-grade content generation plugin for WordPress that automates complex writing workflows. Powered by a highly modular multi-provider AI backend, it enables users to generate optimized blog posts and structured culinary recipes directly inside the WordPress admin dashboard.

**Author:** Mahesh Kumar  
**Website:** https://maheshkumarm.com  



# 📁 Repository Structure

The plugin is packaged as an installable ZIP archive named `auto-content-generator.zip`. The internal structure is organized as follows:

```plaintext
auto-content-generator/
├── auto-content-generator.php
├── license.txt
├── readme.txt
├── security.txt
├── admin/
│   ├── about-page.php
│   ├── assets.php
│   ├── blog-generator-ui.php
│   ├── recipe-generator-ui.php
│   ├── settings-page.php
│   ├── css/
│   │   ├── about-page.css
│   │   ├── blog-generator.css
│   │   ├── recipe-generator.css
│   │   └── settings.css
│   └── js/
│       ├── about-page.js
│       ├── blog-generator.js
│       ├── recipe-generator.js
│       └── settings.js
├── developer_backup/
│   └── auto-content-generator.php_BACKUP
└── includes/
    ├── ai-handler.php
    ├── ai-providers.php
    └── ai-utils.php
```



# 🛠️ Features Overview

## 🤖 Intelligent Multi-LLM API System

- Swappable AI provider architecture (`ai-providers.php`)
- Supports multiple LLM API integrations
- Centralized API key management system
- Flexible model routing and configuration



## ⚙️ Smart AI Orchestration Engine

- Handles asynchronous AI request workflows
- Built-in error handling and fallback systems
- Streamlined response processing pipeline
- Optimized for large content generation tasks



## 🧹 Data Sanitization & Optimization

- Cleans and normalizes AI-generated output
- Fixes malformed JSON structures
- Token optimization for cost control
- Secure input/output validation layer



## ✍️ Content Generation Workspaces

### 📝 Blog Generator

- Generate long-form SEO optimized articles
- Define tone, structure, headings, and keywords
- Ideal for editorial and content marketing workflows

### 🍳 Recipe Generator

- Structured recipe generation system
- Ingredient + step-by-step formatting
- Microdata-ready output for food blogs



## 🎨 Modular UI Architecture

- Fully separated CSS/JS per admin module
- No global UI conflicts
- Lazy-loaded admin assets
- Clean and scalable dashboard interface



## 🔐 API Management System

- Secure API key storage
- Provider validation tools
- Central settings dashboard
- Easy switching between AI providers



# 🚀 Installation Guide

## Option 1: WordPress Dashboard (Recommended)

1. Download `auto-content-generator.zip`
2. Go to WordPress Admin → Plugins → Add New
3. Click Upload Plugin
4. Select ZIP file
5. Click Install Now
6. Activate Plugin



## Option 2: Manual Installation (FTP)

1. Extract `auto-content-generator.zip`
2. Connect via FTP/SFTP
3. Upload folder to `/wp-content/plugins/`
4. Activate plugin from WordPress dashboard



# 💻 Developer Guide

## Core Architecture

- Extend AI providers in:
  `includes/ai-providers.php`

- Modify request logic in:
  `includes/ai-handler.php`

- Utility functions:
  `includes/ai-utils.php`



## UI Extension

- Blog workspace:
  `admin/blog-generator-ui.php`

- Recipe workspace:
  `admin/recipe-generator-ui.php`

Keep UI logic separated from backend handlers for scalability.



## Contribution Workflow

1. Fork repository
2. Create branch:
   ```bash
   git checkout -b feature/new-ai-provider
   ```
3. Commit changes:
   ```bash
   git commit -m "Add new AI provider"
   ```
4. Push branch:
   ```bash
   git push origin feature/new-ai-provider
   ```
5. Open Pull Request


# 📄 License

Developed and maintained by **Mahesh Kumar**  
Under the YemCoders ecosystem.

For enterprise integrations, custom AI models, or portfolio inquiries:  
👉 https://maheshkumarm.com
