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
	subq	$1136, %rsp             ## imm = 0x470
	movl	$2, %eax
	movl	$1, %ecx
	xorl	%edx, %edx
	movl	$0, -4(%rbp)
	movl	%edi, -8(%rbp)
	movq	%rsi, -16(%rbp)
	movl	%eax, %edi
	movl	%ecx, %esi
	callq	_socket
	leaq	L_.str(%rip), %rdi
	xorl	%esi, %esi
	movl	$16, %ecx
	movl	%ecx, %edx
	leaq	-48(%rbp), %r8
	movl	%eax, -20(%rbp)
	movq	%rdi, -1088(%rbp)       ## 8-byte Spill
	movq	%r8, %rdi
	callq	_memset
	movb	$2, -47(%rbp)
	movq	-1088(%rbp), %rdi       ## 8-byte Reload
	movb	$0, %al
	callq	_inet_addr
	movl	$16, %edx
	leaq	-48(%rbp), %rdi
	movl	%eax, -44(%rbp)
	movw	$16414, -46(%rbp)       ## imm = 0x401E
	movl	-20(%rbp), %eax
	movq	%rdi, -1096(%rbp)       ## 8-byte Spill
	movl	%eax, %edi
	movq	-1096(%rbp), %rsi       ## 8-byte Reload
	callq	_bind
	movl	$10, %esi
	movl	-20(%rbp), %edi
	movl	%eax, -1100(%rbp)       ## 4-byte Spill
	callq	_listen
	movl	%eax, -1104(%rbp)       ## 4-byte Spill
LBB0_1:                                 ## =>This Inner Loop Header: Depth=1
	leaq	-28(%rbp), %rdx
	leaq	-64(%rbp), %rax
	movl	$16, -28(%rbp)
	movl	-20(%rbp), %edi
	movq	%rax, %rsi
	callq	_accept
	movl	$2, %edi
	movl	$1000, %ecx             ## imm = 0x3E8
                                        ## kill: RCX<def> ECX<kill>
	leaq	-1072(%rbp), %rdx
	leaq	-64(%rbp), %rsi
	movl	%eax, -24(%rbp)
	addq	$4, %rsi
	movb	$0, %al
	callq	_inet_ntop
	movzwl	-62(%rbp), %edi
	movl	%eax, -1108(%rbp)       ## 4-byte Spill
	callq	__OSSwapInt16
	leaq	L_.str1(%rip), %rdi
	movzwl	%ax, %r8d
	movw	%r8w, %ax
	movzwl	%ax, %edx
	movl	-1108(%rbp), %esi       ## 4-byte Reload
	movb	$0, %al
	callq	_printf
	xorl	%edx, %edx
	movl	%edx, %edi
	movl	%eax, -1112(%rbp)       ## 4-byte Spill
	callq	_time
	leaq	-1072(%rbp), %rcx
	movq	%rax, -1080(%rbp)
	movl	-24(%rbp), %edi
	movl	%edi, -1116(%rbp)       ## 4-byte Spill
	movq	%rcx, %rdi
	movq	%rcx, -1128(%rbp)       ## 8-byte Spill
	callq	_strlen
	movl	-1116(%rbp), %edi       ## 4-byte Reload
	movq	-1128(%rbp), %rsi       ## 8-byte Reload
	movq	%rax, %rdx
	movb	$0, %al
	callq	_write
	movl	-24(%rbp), %edi
	movl	%eax, -1132(%rbp)       ## 4-byte Spill
	movb	$0, %al
	callq	_close
	movl	%eax, -1136(%rbp)       ## 4-byte Spill
	jmp	LBB0_1
	.cfi_endproc

	.align	4, 0x90
__OSSwapInt16:                          ## @_OSSwapInt16
	.cfi_startproc
## BB#0:
	pushq	%rbp
Ltmp3:
	.cfi_def_cfa_offset 16
Ltmp4:
	.cfi_offset %rbp, -16
	movq	%rsp, %rbp
Ltmp5:
	.cfi_def_cfa_register %rbp
	movw	%di, %ax
	movw	%ax, -2(%rbp)
	movzwl	-2(%rbp), %edi
	shll	$8, %edi
	movzwl	-2(%rbp), %ecx
	sarl	$8, %ecx
	orl	%ecx, %edi
	movw	%di, %ax
	movzwl	%ax, %eax
	popq	%rbp
	retq
	.cfi_endproc

	.section	__TEXT,__cstring,cstring_literals
L_.str:                                 ## @.str
	.asciz	"127.0.0.1"

L_.str1:                                ## @.str1
	.asciz	"connection from %d, port %d\n"


.subsections_via_symbols
