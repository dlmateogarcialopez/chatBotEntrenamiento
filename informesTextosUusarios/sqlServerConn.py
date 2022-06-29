import pyodbc

class Connect:

    """Constructor de clase connect"""
    def __init__(self):
        #CONEXION A BASE DE DATOS LOCAL
        # self.conn = pyodbc.connect(
        #     "Driver={SQL Server Native Client 11.0};"
        #     "Server=localhost\SQLEXPRESS;"
        #     "Database=pqr_respuestas;"
        #     "Trusted_Connection=yes;"
        # )

        #CONEXION A BASE DE DATOS SGPQR DESARROLLO
        # self.conn = pyodbc.connect(
        #     # "Driver={/opt/microsoft/msodbcsql17/lib64/libmsodbcsql-17.5.so.2.1};"
        #     "Driver={SQL Server Native Client 11.0};"
        #     "Server=CHEC-BDDS04\CHECSQLDES;"
        #     "Database=SGPQR;"
        #     "UID=intsgpqrdes;"
        #     "PWD=chxXrs758A*1;"
        #     "Trusted_Connection=no;"
        # )

        #CONEXION A BASE DE DATOS SGPQR PRODUCCIÓN
        self.conn = pyodbc.connect(
            "Driver={/opt/microsoft/msodbcsql17/lib64/libmsodbcsql-17.5.so.2.1};"
            # "Driver={SQL Server Native Client 11.0};"
            "Server=CHEC-BDPS04\CHECSQL;"
            "Database=SGPQR;"
            "UID=intsgpqr;"
            "PWD=HC5kQtmadGBGZIa;"
            "Trusted_Connection=no;"
        )

        self.cursor = self.conn.cursor()

        #CONEXION A VUC PARA TRAER LA INFORACIÓN DEL PROCESO
        # self.connVUC = pyodbc.connect(
        #     # "Driver={/opt/microsoft/msodbcsql17/lib64/libmsodbcsql-17.5.so.2.1};"
        #     "Driver={SQL Server Native Client 11.0};"
        #     "Server=CHEC-BDDS04\CHECSQLDES;"
        #     "Database=VUC;"
        #     "UID=VUC_UM;"
        #     "PWD=V4$my062bmdlg;"
        #     "Trusted_Connection=no;"
        # )

        # CONEXION A VUC PRODUCCION
        self.connVUC = pyodbc.connect(
            # "Driver={SQL Server Native Client 11.0};"
            "Driver={/opt/microsoft/msodbcsql17/lib64/libmsodbcsql-17.5.so.2.1};"
            "Server=CHEC-BDPS04\CHECSQL;"
            "Database=VUC;"
            "UID=VUC_UM;"
            "PWD=af39d811f7V;"
            "Trusted_Connection=no;"
        )

        self.cursorVUC = self.connVUC.cursor()


    """ Consultas sql server """
    def executeQuery(self, query, valores = None):
        
        if valores == None:

            self.cursor.execute(query)
        
        else:
        
            self.cursor.execute(query, valores)

        
        return self.cursor.fetchall()

    """ DML sql server"""
    def executeDML(self, query, valores = None):

        if valores == None:

            self.cursor.execute(query)
        
        else:
        
            self.cursor.execute(query, valores)
        
        self.conn.commit()
        
        return self.cursor.rowcount
    
    """ Consultas sql server """
    def executeQueryVUC(self, query, valores = None):
        
        if valores == None:

            self.cursorVUC.execute(query)
            
        else:
        
            self.cursorVUC.execute(query, valores)

        
        return self.cursorVUC.fetchall()

    def executeConsulta(self, proceso):
        return self.executeQueryVUC('''SELECT  DISTINCT
            CONVERT(VARCHAR,P.NUMERO_PROCESO) as requerimiento_chec,
            CONVERT(VARCHAR,PROCESO) as numero_proceso,
            CONVERT(VARCHAR,NOMBRE_SOL) as nombre_sol,
            CONVERT(VARCHAR,CC.NOMBRE) as nombre_propietario,
            CONVERT(VARCHAR,CEDULA_SOL)as cedula_sol,
            CONVERT(VARCHAR,CC.NUMERO_DOCUMENTO)as documento_propietario,
            CONVERT(VARCHAR,convert(VARCHAR,ISNULL(ISNULL(P.TELEFONO_SOL,P.TELEFONO_CONTACTO),P.TELEFONO_CELULAR))) AS telefono,
			CONVERT(VARCHAR,(ISNULL(CC.TELEFONO,CC.TELEFONO_CONTACTO))) AS telefono_cuenta,
			CONVERT(VARCHAR,CC.TELEFONO_CELULAR) AS celular_cuenta,
            CONVERT(VARCHAR(140),p.CORREO) as correo,
            CONVERT(VARCHAR,FECHA_SOL)as fecha_sol,
            CONVERT(VARCHAR,DIRECCION_SOL)as direccion_sol,
            CONVERT(VARCHAR,CC.DIRECCION)as direccion_instalacion,
            CONVERT(VARCHAR,CC.CLIENTE_ID) as niu,
            CONVERT(VARCHAR, ISNULL(ISNULL(ISNULL(NUMERO_RADICADO_AVI,0),NUMERO_RADICADO_PER),NUMERO_RADICADO_WEB)) as radicado,
            P.OBSERVACION as observacion,
            IM.NUMERO_MEDIDOR AS NUMERO_MEDIDOR,
            B.NOM_MUNICIPIO AS nom_mpio,
            B.NOM_DEPARTAMENTO AS nom_dpto,
            B.MSNM AS MSNM,
            (CASE WHEN (B.MSNM >= 1000) THEN 130 WHEN (B.MSNM < 1000) THEN 173 END) AS  consumo_subsidiado,
            P.NUMERO_FACTURA AS NUMERO_FACTURA_RECLAMACION,
            IMC.PERIODO,
            IMC.D_CICLO as ciclo_facturacion
            FROM VUC.FACT_Procesos P
            LEFT JOIN VUC.FACT_Clientes CC
            ON (CC.CLIENTE_ID = P.CLIENTE_ID)
            LEFT JOIN (SELECT   NOM_MUNICIPIO,
                                NOM_DEPARTAMENTO,
                                MSNM,
                                COD_MUNICIPIO,
                                [COD_DEPARTAMENTO],
                                CASE WHEN LEN(COD_MUNICIPIO) = 1 THEN CONCAT ([COD_DEPARTAMENTO]*100,COD_MUNICIPIO)
                                                                    WHEN LEN(COD_MUNICIPIO) = 2 THEN CONCAT ([COD_DEPARTAMENTO]*10,COD_MUNICIPIO)
                                                                    ELSE CONCAT ([COD_DEPARTAMENTO],COD_MUNICIPIO) END COD_MUN
                        FROM [VUC].[dw].[GEOGRAFIA]) B
                        ON B.COD_MUN = CC.MUNICIPIO
            LEFT JOIN VUC.FACT_Ac_Procesos_Pqr FP
            ON (FP.NUMERO_PROCESO = P.NUMERO_PROCESO)
            LEFT JOIN VUC.FACT_Imp_Medidores IM
            ON (IM.CLIENTE_ID = P.CLIENTE_ID)
            LEFT JOIN (SELECT PERIODO,
                            NUMERO_FACTURA, D_CICLO
                        FROM VUC.FACT_Imp_Clientes
                        WHERE PERIODO <> 9999999) IMC
            ON (IMC.NUMERO_FACTURA = P.NUMERO_FACTURA)
            WHERE P.NUMERO_PROCESO = ? ''',
            proceso)
    
    def executeConsulta0(self, valores = None):

        return self.executeQueryVUC(f"""SELECT DISTINCT TOP(?) FC.FECHA_FACTURACION, 
                                        DATEADD(DAY, FC.FECHA_FACTURACION - 2415021, '1900-01-01') as FECHA_FACTURACION,
                                        MED.TIPO_LECTURA,
                                        CONVERT(NUMERIC, ROUND(MED.LECTURA_ACTUAL,0,1)) as LECTURA_TOMADA,
                                        CONVERT(NUMERIC, ROUND(MED.CONSUMO_FACTURADO,0,1)) CONSUMO_FACTURADO,
                                        MED.PROMEDIO AS PROMEDIO,
                                        DATEADD(DAY, FC.FECHA_LECTURA - 2415021, '1900-01-01') as FECHA_LECTURA,
                                        DATEADD(DAY, FECHA_LECTURA_ANT - 2415021, '1900-01-01') as FECHA_LECTURA_ANT,
                                        FC.VALOR AS TARIFA
                                        FROM vuc.FACT_Imp_Clientes FC
                                        LEFT JOIN vuc.FACT_Imp_Medidores MED ON MED.CLIENTE_ID = FC.CLIENTE_ID AND MED.CICLO = FC.CICLO AND MED.PERIODO = FC.PERIODO --AND MED.TIPO_LECTURA = 'A5'
                                        WHERE FC.CLIENTE_ID = ?
                                        AND FC.FECHA_FACTURACION < ?
                                        ORDER BY 1 desc""",
                                    valores)

    def executeConsulta1(self, valores = None):

        return self.executeQueryVUC(f"""SELECT DISTINCT FC.FECHA_FACTURACION as fecha_facturacion,
                                        DATEADD(DAY, FC.FECHA_FACTURACION - 2415021, '1900-01-01') as FECHA_FACTURACION,
                                        CONVERT(NUMERIC, ROUND(MED.LECTURA_ACTUAL,0,1)) as LECTURA_TOMADA,
                                        CONVERT(NUMERIC, ROUND(MED.CONSUMO_FACTURADO,0,1)) CONSUMO_FACTURADO,
                                        DATEADD(DAY, FC.FECHA_LECTURA - 2415021, '1900-01-01') as FECHA_LECTURA,
                                        DATEADD(DAY, FECHA_LECTURA_ANT - 2415021, '1900-01-01') as FECHA_LECTURA_ANT,
                                        FC.VALOR AS TARIFA,
                                        MED.PROMEDIO AS PROMEDIO
                                        FROM vuc.FACT_Imp_Clientes FC
                                        LEFT JOIN vuc.FACT_Imp_Medidores MED ON MED.CLIENTE_ID = FC.CLIENTE_ID AND MED.CICLO = FC.CICLO AND MED.PERIODO = FC.PERIODO --AND MED.TIPO_LECTURA = 'A5'
                                        WHERE FC.NUMERO_FACTURA = ?""",
                                        valores)

    def executeConsulta2(self, valores = None):

        return self.executeQueryVUC(''' SELECT SUM(T.VALOR_TOTAL) as valor_total
                                        FROM (SELECT TOP(6) VALOR_TOTAL
                                                        FROM VUC.FACT_Imp_Conceptos
                                                        WHERE CLIENTE_ID = ?
                                                        AND CODIGO_CONCEPTO = 75
                                                        AND PERIODO <= (SELECT PERIODO
                                                                        FROM VUC.FACT_Imp_Clientes
                                                                        WHERE CLIENTE_ID = ?
                                                                        AND NUMERO_FACTURA = ?
                                                                        AND PERIODO != 9999999)
                                                        ORDER BY PERIODO DESC)AS T  ''',
                                    valores)
    
    def executeConsulta3(self, valores = None):

        return self.executeQueryVUC('''SELECT TOP(1) 
                                        FECHA_FACTURACION, 
                                        DATEADD(DAY, FECHA_FACTURACION - 2415021, '1900-01-01') as FECHA_FACTURACION
                                    FROM vuc.FACT_Imp_Clientes 
                                    WHERE CLIENTE_ID = ?
                                    ORDER BY 1 DESC''',
                                    valores)

    def executeConsulta4(self, valores = None):

        return self.executeQueryVUC('''SELECT TOP(1) 
                                        FECHA_FACTURACION, 
                                        DATEADD(DAY, FECHA_FACTURACION - 2415021, '1900-01-01') as FECHA_FACTURACION
                                    FROM vuc.FACT_Imp_Clientes 
                                    WHERE CLIENTE_ID = ?
                                    ORDER BY 1 DESC''',
                                    valores)

    def executeConsulta5(self, valores = None):

        return self.executeQueryVUC('''SELECT DISTINCT CONVERT(VARCHAR,NUMERO_FACTURA) as NUMERO_FACTURA 
                                        FROM vuc.FACT_PRO_FACTURAS
                                        WHERE NUMERO_PROCESO = ?''',
                                        valores)

    
    