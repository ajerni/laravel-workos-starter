# Laravel WorkOS Starter

A modern Laravel 12 starter project using Inertia.js, Vue 3, WorkOS authentication, and a beautiful UI toolkit.

## Features

- **Laravel 12** – Backend framework with PHP 8.4
- **Inertia.js 2** – Seamless server-driven SPA experience
- **Vue 3** – Reactive frontend framework with TypeScript
- **WorkOS** – Enterprise-grade authentication and user management
- **shadcn-vue** – Beautiful, accessible UI components ([docs](https://www.shadcn-vue.com/docs/installation/laravel.html))
- **Lucide Icons** – Icon library ([browse icons](https://lucide.dev/icons/))
- **Tailwind CSS** – Utility-first CSS framework
- **File Management** – FAQ file upload system with user isolation

## Stack & Tools

### Backend
- Laravel 12 with WorkOS authentication
- Eloquent ORM for database operations
- Policy-based authorization
- File storage with Laravel's public disk
- Request validation with Form Requests

### Frontend
- Vue 3 with Composition API and TypeScript
- Inertia.js for SPA functionality
- shadcn-vue component library
- TailwindCSS for styling
- Vite for fast development and building

## Getting Started

1. **Clone and install dependencies:**
   ```bash
   git clone <repo-url>
   cd aelaravel
   composer install
   npm install
   ```

2. **Environment setup:**
   ```bash
   cp .env.example .env
   ```
   
   Configure your WorkOS credentials in `.env`:
   ```env
   WORKOS_CLIENT_ID=your_client_id
   WORKOS_SECRET=your_secret_key
   WORKOS_REDIRECT_URI=your_redirect_uri
   ```

3. **Database setup:**
   ```bash
   php artisan migrate
   ```

4. **Build assets:**
   ```bash
   npm run build
   # or for development:
   npm run dev
   ```

5. **Start the application:**
   ```bash
   php artisan serve
   ```

## Development

### Running Tests
```bash
composer test
# or
./vendor/bin/pest
```

### Code Quality
```bash
# Format PHP code
./vendor/bin/pint

# Format frontend code
npm run format

# Lint frontend code
npm run lint
```

### Key Features

#### Authentication
- WorkOS integration for user authentication
- Profile management with WorkOS sync
- Secure session handling

#### File Management
- User-specific FAQ file uploads
- Support for PDF, DOC, DOCX, TXT, and MD files
- File size limit: 10MB
- Policy-based access control

#### UI Components
- Modern, accessible components via shadcn-vue
- Responsive design with TailwindCSS
- Dark/light mode support

## Project Structure

```
app/
├── Http/Controllers/
│   ├── FaqFileController.php     # File management
│   └── Settings/ProfileController.php
├── Models/
│   ├── FaqFile.php              # File model
│   └── User.php                 # User model with WorkOS integration
└── Policies/
    └── FaqFilePolicy.php        # File access policies

resources/js/
├── components/
│   ├── FaqFileManager.vue       # File upload component
│   └── ui/                      # shadcn-vue components
└── pages/                       # Inertia pages

routes/
├── web.php                      # Web routes with WorkOS middleware
└── api.php                      # API routes
```

## Production Considerations

1. **File Storage**: Consider using S3 for production file storage
2. **Upload Limits**: Adjust PHP `upload_max_filesize` and `post_max_size` as needed
3. **WorkOS**: Configure production WorkOS environment and webhooks
4. **Caching**: Enable Laravel caching for better performance

---

Built with ❤️ using Laravel, Inertia, Vue, WorkOS, and shadcn-vue. 