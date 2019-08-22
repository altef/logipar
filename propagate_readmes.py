import os, shutil
dir_path = os.path.dirname(os.path.realpath(__file__))

source = '/readme.md'
destinations = [
	'/js/logipar/readme.md',
#	'/python/pip/readme.md'
]

for d in destinations:
	shutil.copyfile("{}{}".format(dir_path, source), "{}{}".format(dir_path, d))
