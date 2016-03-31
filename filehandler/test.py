f1 = open('file1.txt')
f2 = open('file2.txt')
f3 = open('file3.txt', 'a+')
for line1,line2 in zip(f1.readlines(), f2.readlines()):
	line1 = line1.strip(' \n')
	line2 = line2.strip(' \n')
	if len(line1)==0 and len(line2)==0:
		f3.write("\n")
	else:
		f3.write(line1+','+line2+',')