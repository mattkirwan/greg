#Greg

A CLI tool for generating API class controllers complete with endpoint methods.

```
	bin/greg build staff[c,r,u,d,l] products[c,u] news[c,r,u,d] agents[c,l]
```

Will build controllers with the appropriate methods:

```
	staff
		create
		read
		update
		delete
		list

	products
		create
		update

	news
		create
		read
		update
		delete

	agents
		create
		list
```