<p align="center">
<img height="80" src="https://laravel.com/img/logomark.min.svg">
<img height="80" src="https://vuejs.org/images/logo.png" alt="Vue logo">
<img height="80" src="https://cdn.worldvectorlogo.com/logos/element-ui-1.svg">
</p>

[![MIT Licensed](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat)](LICENSE)

## Extensions

- BackEnd: [Laravel 9](https://laravel.com/)
- FrontEnd: [Vue3 Composition Api](https://vuejs.org) + [VueRouter](https://router.vuejs.org) + [Pinia](https://pinia.vuejs.org) + [VueI18n](https://kazupon.github.io/vue-i18n/)
- Login using [Vue-Auth](https://websanova.com/docs/vue-auth/home), [Axios](https://github.com/mzabriskie/axios) and [Sanctum](https://laravel.com/docs/8.x/sanctum).
- The api routes, are separate for each module, in **Modules/{ModuleName}/routes_api.php**
- [ElementPlus](https://element-plus.org/) UI Kit 
- [Lodash](https://lodash.com) js utilities
- [Day.js](https://dayjs.com) time manipulations
- [FontAwesome 5](http://fontawesome.io/icons/) icons

## Install
- `git clone https://github.com/Yurich84/todo-1.git`
- `cd laravel-vue-spa-skeleton`
- `composer install`
- `cp .env.example .env` - copy .env file
- set your DB credentials in `.env`
- `php artisan key:generate`
- `php artisan migrate`
- `yarn install`

## Testing

### Unit Testing
`php artisan test`

## Usage
- `npm run dev` - for hot reloading
- `php artisan serve` and go [localhost:8000](http://localhost:8000)
- `php artisan schedule:work` - to run schedule tasks

