require('dotenv').config()

const env = process.env;

require('laravel-echo-server').run({
    authHost: env.SOCKET_AUTH_HOST,
    authEndpoint: '/broadcasting/auth',
    devMode: env.APP_DEBUG,
    database: 'redis',
    databaseConfig: {
        redis: {
            host: env.REDIS_HOST,
            password: env.REDIS_PASSWORD,
            port: env.REDIS_PORT,
            keyPrefix: ''
        }
    },
    apiOriginAllow: {
		allowCors: false,
		allowOrigin: '',
		allowMethods: '',
		allowHeaders: ''
	}
});
