package main

//import "errors"
import "fmt"

//import "math"

const (
    Sunday = iota
    Monday
    Tuesday
    Wednesday
    Thursday
    Friday
    Saturday
    // 0
    // 1,通常省略后续⾏行表达式。 //2
    //3
    //4
    //5
    //6
)

type Color int

const (
    RED Color = iota
    BLACK
    BLUE
)

func test(c Color) {}

func main() {
    const HEY int = 2
    data, i := [3]int{0, 1, 2}, 0
    i, data[i] = 2, 100

    test(BLACK)
    test_a :=1
    test(test_a) //cannot use test_a (type int) as type Color in argument to test

    fmt.Println(i, data[0], data[2], HEY, Saturday)
}
