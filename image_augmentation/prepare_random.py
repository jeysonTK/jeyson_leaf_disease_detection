import os
import random
from os.path import exists

test_dir="./train"
validation_dir="./validation"
annotations_dir='/annotations'
imeges_dir='/images'

labels = { "healthy": 0, "black_root": 0, "scab": 0 }  

dir_content = os.listdir( test_dir+annotations_dir )

no_of_files_too_move = len(dir_content) / 4 
succes_moved=0
while succes_moved < no_of_files_too_move :
	random_num=random.randint(0,len(dir_content)-1)
	file_exists = exists(test_dir+annotations_dir+"/"+dir_content[random_num])
	if file_exists:
		if "healthy" in dir_content[random_num] and labels["healthy"] >= no_of_files_too_move/3 :
			continue
		if "black_root" in dir_content[random_num] and labels["black_root"] >=  no_of_files_too_move/3 :
			continue
		if "scab" in dir_content[random_num] and labels["scab"] >=  no_of_files_too_move/3 :
			continue
					
		succes_moved=succes_moved+1
		os.rename(test_dir+annotations_dir+"/"+dir_content[random_num], validation_dir+annotations_dir+"/"+dir_content[random_num])
		os.rename(test_dir+imeges_dir+"/"+dir_content[random_num].split(".")[0]+".JPG", validation_dir+imeges_dir+"/"+dir_content[random_num].split(".")[0]+".JPG")
		
		if "healthy" in dir_content[random_num]:
			labels["healthy"]=labels["healthy"]+1
		if "black_root" in dir_content[random_num]  :
			labels["black_root"]=labels["black_root"]+1
		if "scab" in dir_content[random_num]  :
			labels["scab"]=labels["scab"]+1
				
print(labels["healthy"])
print(labels["black_root"])
print(labels["scab"])
	

