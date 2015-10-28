package main

//import "fmt"
func main() {
	s := "abc"
	for i, n := 0, len(s); i < n; i++ {
		println(s[i])
	}
	a := "abc"

	for j, k := range s {
		println(j, k)
	}
	c := 1
	d := 3
	e := 4
	f := 8
	g = 1
	m := map[string]int{"a": 1, "b": 2}
}
