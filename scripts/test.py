import hashlib
import time

i = 1
j = 0

testlen = 10
while(1):
    starttime = time.time()
    while(1):
        code = hashlib.sha256(str(i).encode()).hexdigest()
        flag = 0
        for j in range(0, testlen):
            print(code)
            right = 2*j + 2;
            left = 2*j + 1;
            if (left >= testlen or right >= testlen):
                break
            if(ord(code[left]) > ord(code[j]) or ord(code[right]) < ord(code[j])):
                flag = 1
                break
        if(flag == 0):
            print(code)
            break
        i += 1
    finishtime = time.time()
    print(finishtime - starttime)
    break
