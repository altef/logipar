import setuptools

with open("../../readme.md", "r", encoding="utf-8") as fh:
	long_description = fh.read()

setuptools.setup(
	name="logipar",
	version="0.6.0",
	author="Brad Gill",
	author_email="brad@alteredeffect.com",
	description="A logic string parser",
	long_description=long_description,
	long_description_content_type="text/markdown",
	url="https://github.com/altef/logipar",
	packages=setuptools.find_packages(),
	classifiers=[
		"Programming Language :: Python :: 3",
		"License :: OSI Approved :: MIT License",
		"Operating System :: OS Independent",
	],
)