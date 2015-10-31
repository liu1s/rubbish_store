package main

import "fmt"

func main() {
	test3()
}

func test3() {
	type List struct {
		data struct {
			name string
			sex  string
		}
		next *List
	}

	first := List{
		//data: {"wo shi 1", "male"}, // Error: missing type in composite literal
		next: nil,
	}
	first.data.name = "wo shi 1"
	first.data.sex = "male"

	second := List{
		//data: {"wo shi 2", "female"},
		next: &first,
	}
	second.data.name = "wo shi 2"
	second.data.sex = "female"

	fmt.Println(second.next.data.name, second.next.data.sex)
}

func test2() {
	data := [...]int{0, 1, 2, 3, 4, 10: 0}
	s := data[:2:3]
	//s = append(s, 100)
	s = append(s, 100, 200)      // ⼀一次 append 两个值,超出 s.cap 限制。
	fmt.Println(s, data)         // 重新分配底层数组,与原数组⽆无关。
	fmt.Println(&s[0], &data[0]) // ⽐比对底层数组起始指针。

}

func test1() {
	a := [5]int{2: 100, 4: 200}

	for _, b := range a {
		println(b)
	}
	b := [...]int{1, 2, 3, 4}
	println(len(b))

	c := [...]struct {
		name string
		age  uint8
	}{
		{"wallace", 1},
		{"leo", 2},
	}
	for key, row := range c {
		println(key, row.name)
	}

	data := [...]int{0, 1, 2, 3, 4, 5, 6, 7, 8, 9}
	println(data[:6:8][1], data[5:], data[:3], data[:])
	fmt.Println(data[:6:8][1], data[5:], data[:3], data[:])
}
