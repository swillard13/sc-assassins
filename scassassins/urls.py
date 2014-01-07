from scassassins.assassins.views import home

from django.conf.urls import patterns, include, url
from django.views.generic import TemplateView

from django.contrib import admin
admin.autodiscover()

urlpatterns = patterns('',
    url(r'^$', home, name='home'),
    #url(r'^create_game/', create_game, name='create_game'),
    url(r'^facebook_debug/', TemplateView.as_view(template_name='facebook_debug.html')),
    url(r'^admin/', include(admin.site.urls)),
)
