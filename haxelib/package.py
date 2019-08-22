from zipfile import ZipFile 
import glob

offset = "../"

file = 'logipar.zip'
with ZipFile(file, 'w') as zip:
	zip.write('haxelib.json')
	for file in glob.glob("{}/src/logipar/*.hx".format(offset)):
		zip.write(file, arcname=file.replace(offset, ''))

print("Packaging complete.")
