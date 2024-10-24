<?php

class Consulta {

    public $sqlNuevos;
    public $sql;

    public function __construct()
    {

        $this->sqlNuevos = "
        
        SET DATEFORMAT YMD
        INSERT INTO SOF_CONFIRMA (FECHA_MOV, NCOMP_ORIG, N_COMP, NCOMP_IN_S, COD_ARTICU, DESCRIPCIO, CANT, COD_NUEVO, N_ORDEN_CO, ENVIADO, ID_STA20)
        (
        
            SELECT A.* FROM
            (
                SELECT CAST(A.FECHA_MOV AS DATE)FECHA_MOV, CASE WHEN A.NCOMP_ORIG = '' THEN 'TRANSFERENCIA' ELSE A.NCOMP_ORIG END NCOMP_ORIG
                , A.N_COMP, A.NCOMP_IN_S, B.COD_ARTICU, C.DESCRIPCIO, CAST(B.CANTIDAD AS INT)CANT, '' COD_NUEVO, B.N_ORDEN_CO,
                ROW_NUMBER() OVER (ORDER BY A.NCOMP_ORIG) ENVIADO, B.ID_STA20
                FROM STA14 A
                INNER JOIN STA20 B
                ON A.NCOMP_IN_S = B.NCOMP_IN_S AND A.TCOMP_IN_S = B.TCOMP_IN_S
                INNER JOIN STA11 C
                ON B.COD_ARTICU = C.COD_ARTICU
                AND B.COD_DEPOSI = 'OU'
                AND B.N_ORDEN_CO = ''
                AND A.T_COMP IN ('TRA')
                AND B.TIPO_MOV = 'E'
                AND A.FECHA_MOV >= GETDATE()-180
                --AND A.USUARIO_INGRESO NOT LIKE 'CONTROL%'
                AND A.USUARIO != 'IFC'
            )A 
            FULL OUTER JOIN SOF_CONFIRMA B ON A.FECHA_MOV = B.FECHA_MOV AND A.N_COMP = B.N_COMP COLLATE Modern_Spanish_CI_AI
            WHERE B.N_COMP IS NULL
        
        )
        
        ";
        
        $this->sql =
        "
        SET DATEFORMAT YMD
        SELECT CAST(A.FECHA_MOV AS DATE)FECHA_MOV, CASE WHEN A.NCOMP_ORIG = '' THEN 'TRANSFERENCIA' ELSE A.NCOMP_ORIG END NCOMP_ORIG,  
        A.N_COMP, A.NCOMP_IN_S, B.COD_ARTICU, C.DESCRIPCIO, CAST(B.CANTIDAD AS INT)CANT, COD_NUEVO, B.N_ORDEN_CO, B.ID_STA20, D.RECHAZADO
        FROM STA14 A
        INNER JOIN STA20 B ON A.NCOMP_IN_S = B.NCOMP_IN_S AND A.TCOMP_IN_S = B.TCOMP_IN_S
        INNER JOIN STA11 C ON B.COD_ARTICU = C.COD_ARTICU
        INNER JOIN SOF_CONFIRMA D ON B.COD_ARTICU = D.COD_ARTICU COLLATE Latin1_General_BIN AND A.TCOMP_IN_S = B.TCOMP_IN_S AND A.NCOMP_IN_S = D.NCOMP_IN_S COLLATE Latin1_General_BIN  AND B.ID_STA20 = D.ID_STA20
        AND B.COD_DEPOSI = 'OU'
        AND D.N_ORDEN_CO = ''
        AND A.T_COMP IN ('TRA')
        AND A.FECHA_MOV >= GETDATE()-180
        --AND A.USUARIO_INGRESO NOT LIKE 'CONTROL%'
        AND A.USUARIO != 'IFC'
        AND D.RECHAZADO IS NULL
        ORDER BY 2, 5
        "
        ;
    }

    
    
    
    /* CREAR LA VISTA Y EL SP EN LA BASE DE CADA LOCAL
    
    CREATE TABLE SOF_CONFIRMA(
    FECHA_MOV DATE, 
    NCOMP_ORIG VARCHAR(14) COLLATE Latin1_General_BIN,
    N_COMP VARCHAR(14) COLLATE Latin1_General_BIN,
    NCOMP_IN_S VARCHAR(8) COLLATE Latin1_General_BIN,
    COD_ARTICU VARCHAR(15) COLLATE Latin1_General_BIN,
    DESCRIPCIO VARCHAR(30) COLLATE Modern_Spanish_CI_AI,
    CANT INT,
    COD_NUEVO VARCHAR(40),
    N_ORDEN_CO VARCHAR(14) COLLATE Latin1_General_BIN, 
    ENVIADO INT,
    ID int IDENTITY(1,1) PRIMARY KEY,
    ID_STA20 INT,
    RECHAZADO INT
    )
    
    
    
    
    CREATE PROCEDURE SP_SJ_ARTICULO_OUTLET (@A VARCHAR(15), @B INT)  
    AS  
    BEGIN  
    DECLARE @NUEVO_CODIGO VARCHAR(15) = @A  
    DECLARE @CANTIDAD INT = @B  
    DECLARE @DEPOSITO VARCHAR(2) = (SELECT CAST((SELECT NRO_SUCURSAL FROM SUCURSAL WHERE ID_SUCURSAL IN (SELECT ID_SUCURSAL FROM EMPRESA))AS VARCHAR))  
      
    IF EXISTS (SELECT * FROM STA19 WHERE COD_ARTICU = @NUEVO_CODIGO)  
    BEGIN  
    UPDATE STA19 SET CANT_STOCK = CANT_STOCK + @CANTIDAD WHERE COD_ARTICU = @NUEVO_CODIGO AND COD_DEPOSI = @DEPOSITO  
    END   
    ELSE   
    BEGIN  
      
    INSERT INTO STA19 (FILLER, CANT_COMP, CANT_PEND, CANT_STOCK, COD_ARTICU, COD_DEPOSI, FECHA_ANT, LOTE, SALDO_ANT, EXP_SALDO, COD_UBIC1, COD_UBIC2, COD_UBIC3, UBIC_TXT, CANT_COMP_2, CANT_PEND_2,   
    CANT_STOCK_2, SALDO_ANT_STOCK_2)  
    VALUES ('', 0, 0, @CANTIDAD, @NUEVO_CODIGO, @DEPOSITO, CAST(GETDATE()AS DATE), 0, 0, 0, '', '', '', '', 0, 0, 0, 0)  
      
    END  
      
    END
    
    */
}
