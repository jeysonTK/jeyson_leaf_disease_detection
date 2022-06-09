#!/usr/bin/python
from imageai.Detection.Custom import DetectionModelTrainer
from xml.dom import minidom

configuration = minidom.parse( "config.xml" )
config_dataset = configuration.getElementsByTagName( "dataset" )[0]
config_train = configuration.getElementsByTagName( "train" )[0]

trainer = DetectionModelTrainer()
trainer.setModelTypeAsYOLOv3()
trainer.setDataDirectory(data_directory=config_dataset.getAttribute( 'path' ))
trainer.setTrainConfig(object_names_array=["healthy_apple_leaf", "apple_black_rot", "damaged_apple_leaf", "apple_scab"], batch_size=int( config_train.getAttribute( 'batch' ) ), num_experiments=int( config_train.getAttribute( 'experiments' ) ), train_from_pretrained_model=config_train.getAttribute( 'preTrainedModel' ))
trainer.trainModel()
