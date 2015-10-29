package main

//import "fmt"
func main() {
	s := "abc"
	for i, n := 0, len(s); i < n; i++ {
		println(s[i])
	}

	goto hahahaha
hahahaha:
	println("hahahaha")

	for j, k := range s {
		println(j, k)
	}
	m := map[string]int{"a": 1, "b": 2}
	for l, m := range m {
		println(l, m)
	}
	a := 1
	switch a {
	case 2:
		println("xxxx")
	case 1:
		println("xxxx1")
		fallthrough
	default:
		println("default")
	}

}
