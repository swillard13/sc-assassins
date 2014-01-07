from django.db import models
from django.contrib.auth.models import User

class Game(models.Model):
	admin = models.ForeignKey(User)
	title = models.TextField()
	start_date = models.DateTimeField()
	
class Player(models.Model):
	user = models.ForeignKey(User, related_name='+')
	game = models.ForeignKey(Game)
	killer = models.ForeignKey(User, related_name='+')
	target = models.ForeignKey(User, related_name='+')