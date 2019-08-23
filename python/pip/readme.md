I ran the following with python3:

Remember to update the version in [setup.py](setup.py).

1. `python setup.py sdist bdist_wheel`
2. `python -m twine upload dist/*`
