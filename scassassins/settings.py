"""
Django settings for scassassins project.

For more information on this file, see
https://docs.djangoproject.com/en/1.6/topics/settings/

For the full list of settings and their values, see
https://docs.djangoproject.com/en/1.6/ref/settings/
"""

# Build paths inside the project like this: os.path.join(BASE_DIR, ...)
import os
BASE_DIR = os.path.dirname(os.path.dirname(__file__))


# Quick-start development settings - unsuitable for production
# See https://docs.djangoproject.com/en/1.6/howto/deployment/checklist/

# SECURITY WARNING: keep the secret key used in production secret!
SECRET_KEY = 'h$4+fchl=#i941a0xsf%hbv&&qnu+)z-$g)s_1j*$7kk)nz40b'

# SECURITY WARNING: don't run with debug turned on in production!
DEBUG = True

TEMPLATE_DEBUG = True

ALLOWED_HOSTS = []


# Application definition

INSTALLED_APPS = (
    'django.contrib.admin',
    'django.contrib.auth',
    'django.contrib.contenttypes',
    'django.contrib.sessions',
    'django.contrib.messages',
    'django.contrib.staticfiles',
    'django_facebook',
    'scassassins.assassins',
)

MIDDLEWARE_CLASSES = (
    'django.contrib.sessions.middleware.SessionMiddleware',
    'django.middleware.common.CommonMiddleware',
    'django.middleware.csrf.CsrfViewMiddleware',
    'django.contrib.auth.middleware.AuthenticationMiddleware',
    'django.contrib.messages.middleware.MessageMiddleware',
    'django.middleware.clickjacking.XFrameOptionsMiddleware',
    'django_facebook.middleware.FacebookMiddleware',
)

AUTHENTICATION_BACKENDS = (
    'django_facebook.auth.FacebookBackend',
    'django_facebook.auth.FacebookProfileBackend',
    'django.contrib.auth.backends.ModelBackend',
)

ROOT_URLCONF = 'scassassins.urls'

WSGI_APPLICATION = 'scassassins.wsgi.application'


# Database
# https://docs.djangoproject.com/en/1.6/ref/settings/#databases

DATABASES = {
    'default': {
        'ENGINE': 'django.db.backends.sqlite3',
        'NAME': os.path.join(BASE_DIR, 'db.sqlite3'),
    }
}

# Internationalization
# https://docs.djangoproject.com/en/1.6/topics/i18n/

LANGUAGE_CODE = 'en-us'

TIME_ZONE = 'UTC'

USE_I18N = True

USE_L10N = True

USE_TZ = True


# Static files (CSS, JavaScript, Images)
# https://docs.djangoproject.com/en/1.6/howto/static-files/

STATIC_URL = '/static/'

FACEBOOK_APP_ID = '247134442121359'

# Optionally set default permissions to request, e.g: ['email', 'user_about_me']
FACEBOOK_SCOPE = ['user_groups']

# And for local debugging, use one of the debug middlewares and set:
FACEBOOK_DEBUG_TOKEN = ''
FACEBOOK_DEBUG_UID = ''
FACEBOOK_DEBUG_COOKIE = ''
FACEBOOK_DEBUG_SIGNEDREQ = ''

# Optionally throw exceptions instead of returning HTTP errors on signed request issues
FACEBOOK_RAISE_SR_EXCEPTIONS = True

from settings_local import *