from django.shortcuts import render

def home(request):
	context = {}
	if request.facebook:
		context['profile'] =  request.facebook.graph.get_object('me')
	return render(request, 'home.html', context)

def create_game(request):
	if request.POST:
		print('Hello')