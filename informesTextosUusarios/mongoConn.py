from pymongo import MongoClient

class mongoConnect:

    """Constructor de clase connect"""
    def __init__(self):
        #CONEXION A MONGO DB PARA FACTURACION
        self.client = MongoClient("mongodb://Int_Facturacion:Int_Facturacion@chec-apd08:27017/?authSource=Int_Facturacion&readPreference=primary&appname=MongoDB%20Compass&ssl=false", retryWrites=False)

        self.db = self.client['Int_Facturacion']
    
    def executeAggregation(self, collection, aggregation):
        
        agg = self.db[collection].aggregate(aggregation)

        return list(agg)

