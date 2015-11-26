package main

import "fmt"

type Resource struct {
	id int
}

type User struct {
	Resource
	name string
}

type Manager struct {
	User
	title string
}

func main() {
	var m1 Manager
	m1.id = 1
	m1.name = "刘爽"
	m1.title = "帅帅哒"
	println(m1.id, m1.name, m1.title)

	m2 := Manager{
		User{
			Resource{2},
			"李卷毛",
		},
		"22哒",
	}
	println(m2.id, m2.name, m2.title)
}
