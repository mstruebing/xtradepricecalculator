module Main where

import System.Environment

data Item = Iron | Water | Food | Steel | Electronics

iron_default_price :: Int
iron_default_price = 1
water_default_price :: Int
water_default_price = 2
food_default_price :: Int
food_default_price = 3
steel_default_price :: Int
steel_default_price = 4
eleectronics_default_price :: Int
eleectronics_default_price = 5

main = do 
    args <- getArgs
    let iron = dropWhile (/= "--iron") args
    let water = dropWhile (/= "--water") args
    let food = dropWhile (/= "--food") args
    let steel= dropWhile (/= "--steel") args
    let electronics = dropWhile untilNextArgument $ args
    let ironList = takeWhile untilNextArgument $ tail iron
    let ironAmount = sum $ map (\x -> read x :: Double) ironList
    let waterList = takeWhile untilNextArgument $ tail water
    let waterAmount = sum $ map (\x -> read x :: Double) waterList
    let foodList = takeWhile untilNextArgument $ tail food
    let foodAmount = sum $ map (\x -> read x :: Int) foodList
    let steelList = takeWhile untilNextArgument $ tail steel
    let steelAmount = sum $ map (\x -> read x :: Int) steelList
    let electronicsList = takeWhile untilNextArgument $ tail electronics
    let electronicsAmount = sum $ map (\x -> read x :: Int) electronicsList

    let ironPrice = ironAmount / (fromIntegral $ length ironList)
    let waterPrice = waterAmount / (fromIntegral $ length waterList)
    let foodPrice = ironAmount / (fromIntegral $ length ironList)
    let steelPrice = waterAmount / (fromIntegral $ length waterList)

    putStrLn $ show ironPrice 
    putStrLn $ show waterPrice
    putStrLn $ show foodAmount
    putStrLn $ show steelAmount
    putStrLn $ show electronicsAmount

    where
        untilNextArgument x = head x /= '-'
