#!/usr/bin/python
from imageai.Detection.Custom import CustomObjectDetection
from xml.dom import minidom
import pymysql
import sys
import os

configuration = minidom.parse( 	"config.xml" )
database=configuration.getElementsByTagName( "database" )[0]
model=configuration.getElementsByTagName( "model" )[0]
directories=configuration.getElementsByTagName( "directories" )[0]

#Database connection
conn = pymysql.connect( 
	 host = database.getAttribute( 'host' ),
	 port = int( database.getAttribute( 'port' ) ), 
	 user = database.getAttribute( 'username' ), 
	 passwd = database.getAttribute( 'password' ), 
	 db = 'mysql' )
cursor = conn.cursor() 

#Prepare model
detector = CustomObjectDetection() 
detector.setModelTypeAsYOLOv3() 
detector.setModelPath( detection_model_path=model.getAttribute( 'path' ) )
detector.setJsonPath( configuration_json=model.getAttribute( 'json' ) )
detector.loadModel() 

uploads_dir=directories.getAttribute( 'base' ) +directories.getAttribute( 'uploads' ) 
analized_dir=directories.getAttribute( 'base' ) +directories.getAttribute( 'analized' ) 
original_dir=directories.getAttribute( 'base' ) +directories.getAttribute( 'original' ) 

while 1 :
	 #List all files from watch directory
	dir_content = os.listdir( uploads_dir )
	if len( dir_content ) > 0 :
		source=uploads_dir + dir_content[0]
		destination=analized_dir + dir_content[0]
		destionation_o=original_dir + dir_content[0]
		
		#Start detection
		detections = detector.detectObjectsFromImage( input_image=source, minimum_percentage_probability=85, output_image_path=destination )
		
		#Save image
		sql = "INSERT INTO jeyson_leaf.users_leaf ( username, original_image_path, analized_image_path, datetime, location ) VALUES ( %s, %s, %s, %s, %s )"
		db_params=dir_content[0].split( "--u--" )
		val = ( db_params[0], directories.getAttribute( 'original' ) + dir_content[0], directories.getAttribute( 'analized' ) +dir_content[0],db_params[1],db_params[2] )
		cursor.execute( sql, val )
		conn.commit() 
		
		print( cursor.rowcount, "record inserted." )
		
		users_leaf_id = cursor.lastrowid
		
		#Save detections 
		sql = "INSERT INTO jeyson_leaf.detections ( users_leaf_id, name, percentage_probability, box_points ) VALUES ( %s, %s, %s, %s )"
		for detection in detections:
			print(str(users_leaf_id)+detection["name"]+str(detection["percentage_probability"])+str(detection["box_points"] ))
			val = ( users_leaf_id, detection["name"], str( detection["percentage_probability"] ), str( detection["box_points"] ) )
			cursor.execute( sql, val )
			conn.commit() 
		
		
		os.rename( source, destionation_o )
		
