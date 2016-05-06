	.section	__TEXT,__text,regular,pure_instructions
	.macosx_version_min 10, 11
	.globl	_main
	.align	4, 0x90
_main:                                  ## @main
	.cfi_startproc
## BB#0:
	pushq	%rbp
Ltmp0:
	.cfi_def_cfa_offset 16
Ltmp1:
	.cfi_offset %rbp, -16
	movq	%rsp, %rbp
Ltmp2:
	.cfi_def_cfa_register %rbp
	subq	$64, %rsp
	movl	$0, -4(%rbp)
	callq	_rand
	leaq	L_.str(%rip), %rdi
	movl	$10, %ecx
	cltd
	idivl	%ecx
	movl	%edx, %esi
	movb	$0, %al
	callq	_printf
	movl	%eax, -8(%rbp)          ## 4-byte Spill
	callq	_rand
	leaq	L_.str(%rip), %rdi
	movl	$10, %ecx
	cltd
	idivl	%ecx
	movl	%edx, %esi
	movb	$0, %al
	callq	_printf
	movl	%eax, -12(%rbp)         ## 4-byte Spill
	callq	_rand
	leaq	L_.str(%rip), %rdi
	movl	$10, %ecx
	cltd
	idivl	%ecx
	movl	%edx, %esi
	movb	$0, %al
	callq	_printf
	movl	%eax, -16(%rbp)         ## 4-byte Spill
	callq	_rand
	leaq	L_.str(%rip), %rdi
	movl	$10, %ecx
	cltd
	idivl	%ecx
	movl	%edx, %esi
	movb	$0, %al
	callq	_printf
	movl	%eax, -20(%rbp)         ## 4-byte Spill
	callq	_rand
	leaq	L_.str(%rip), %rdi
	movl	$10, %ecx
	cltd
	idivl	%ecx
	movl	%edx, %esi
	movb	$0, %al
	callq	_printf
	movl	%eax, -24(%rbp)         ## 4-byte Spill
	callq	_rand
	leaq	L_.str(%rip), %rdi
	movl	$10, %ecx
	cltd
	idivl	%ecx
	movl	%edx, %esi
	movb	$0, %al
	callq	_printf
	movl	%eax, -28(%rbp)         ## 4-byte Spill
	callq	_rand
	leaq	L_.str(%rip), %rdi
	movl	$10, %ecx
	cltd
	idivl	%ecx
	movl	%edx, %esi
	movb	$0, %al
	callq	_printf
	movl	%eax, -32(%rbp)         ## 4-byte Spill
	callq	_rand
	leaq	L_.str(%rip), %rdi
	movl	$10, %ecx
	cltd
	idivl	%ecx
	movl	%edx, %esi
	movb	$0, %al
	callq	_printf
	movl	%eax, -36(%rbp)         ## 4-byte Spill
	callq	_rand
	leaq	L_.str(%rip), %rdi
	movl	$10, %ecx
	cltd
	idivl	%ecx
	movl	%edx, %esi
	movb	$0, %al
	callq	_printf
	movl	%eax, -40(%rbp)         ## 4-byte Spill
	callq	_rand
	leaq	L_.str(%rip), %rdi
	movl	$10, %ecx
	cltd
	idivl	%ecx
	movl	%edx, %esi
	movb	$0, %al
	callq	_printf
	movl	%eax, -44(%rbp)         ## 4-byte Spill
	callq	_rand
	leaq	L_.str(%rip), %rdi
	movl	$10, %ecx
	cltd
	idivl	%ecx
	movl	%edx, %esi
	movb	$0, %al
	callq	_printf
	movl	%eax, -48(%rbp)         ## 4-byte Spill
	callq	_rand
	leaq	L_.str(%rip), %rdi
	movl	$10, %ecx
	cltd
	idivl	%ecx
	movl	%edx, %esi
	movb	$0, %al
	callq	_printf
	xorl	%ecx, %ecx
	movl	%eax, -52(%rbp)         ## 4-byte Spill
	movl	%ecx, %eax
	addq	$64, %rsp
	popq	%rbp
	retq
	.cfi_endproc

	.section	__TEXT,__cstring,cstring_literals
L_.str:                                 ## @.str
	.asciz	"rand: %d\n"


.subsections_via_symbols
