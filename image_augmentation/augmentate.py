import os
import math
import cv2
from xml.dom import minidom

augmentation_prefix="JY-AG"
annotations_dir='./annotations'
imeges_dir='./images'
dir_content = os.listdir( annotations_dir )

for xml_file in dir_content:
	print (xml_file)
	if not xml_file.startswith(augmentation_prefix):
		xml_parse = minidom.parse( annotations_dir+"/"+xml_file )
		image_filename=xml_parse.getElementsByTagName('filename')[0].firstChild.data
		image_path=imeges_dir+"/"+image_filename
		image_size=256
		
		rotations = [augmentation_prefix+"-90", augmentation_prefix+"-180", augmentation_prefix+"-270"]
		for rotation in rotations :
			object_counter=0
			#Modify filname tag for object
			xml_parse.getElementsByTagName('filename')[0].firstChild.data=rotation+"_"+image_filename
			xml_parse.getElementsByTagName('path')[0].firstChild.data=os.path.dirname(xml_parse.getElementsByTagName('path')[0].firstChild.data)+"/"+rotation+"_"+image_filename
			
			for elem in xml_parse.getElementsByTagName('object'):
				
				im = cv2.imread(image_path)
				_, w, _ = im.shape
				print(w)
				xmin=int(elem.getElementsByTagName('xmin')[0].firstChild.data) 
				ymin=int(elem.getElementsByTagName('ymin')[0].firstChild.data) 
				xmax=int(elem.getElementsByTagName('xmax')[0].firstChild.data)
				ymax=int(elem.getElementsByTagName('ymax')[0].firstChild.data)
				
				new_xmin = w - 1 - ymax 
				new_ymin = xmin
				
				new_xmax = w - 1 - ymin 
				new_ymax = xmax
				elem.getElementsByTagName('xmin')[0].firstChild.data = new_xmin
				elem.getElementsByTagName('ymin')[0].firstChild.data = new_ymin
				elem.getElementsByTagName('xmax')[0].firstChild.data = new_xmax
				elem.getElementsByTagName('ymax')[0].firstChild.data = new_ymax
				object_counter=object_counter+1
				
			image_path=imeges_dir+"/"+rotation+"_"+image_filename
			im = cv2.rotate(im, cv2.cv2.ROTATE_90_CLOCKWISE)
			cv2.imwrite(image_path, im)
			
			new_adnontation_file = open(annotations_dir+"/"+rotation+"_"+xml_file, 'w')
			xml_parse.writexml( new_adnontation_file )
			new_adnontation_file.close()	
	
		
